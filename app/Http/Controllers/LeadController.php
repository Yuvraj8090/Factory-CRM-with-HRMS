<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\EmailLog;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\SalesTeam;
use App\Models\User;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LeadController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $leads = Lead::with(['stage', 'assignedTo', 'assignedTeam', 'convertedCustomer'])
            ->when($request->filled('lead_stage_id'), fn ($query) => $query->where('lead_stage_id', $request->integer('lead_stage_id')))
            ->when($request->filled('assigned_to'), fn ($query) => $query->where('assigned_to', $request->integer('assigned_to')))
            ->when($request->boolean('open_only'), fn ($query) => $query->open())
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($leads);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'lead_stages' => LeadStage::query()->orderBy('stage_order')->get(),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
            'sales_teams' => SalesTeam::active()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateLead($request);
        $data['created_by'] = auth()->id();
        $data['is_converted'] = $data['is_converted'] ?? false;

        $lead = Lead::create($data)->load(['stage', 'assignedTo', 'assignedTeam', 'convertedCustomer']);

        return response()->json($lead, 201);
    }

    public function show(Lead $lead): JsonResponse
    {
        return response()->json($lead->load(['stage', 'assignedTo', 'assignedTeam', 'convertedCustomer', 'activities.type', 'activities.status', 'emailLogs', 'whatsAppMessages.template']));
    }

    public function edit(Lead $lead): JsonResponse
    {
        return response()->json([
            'lead' => $lead->load(['stage', 'assignedTo', 'assignedTeam', 'convertedCustomer']),
            'lead_stages' => LeadStage::query()->orderBy('stage_order')->get(),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
            'sales_teams' => SalesTeam::active()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Lead $lead): JsonResponse
    {
        $lead->update($this->validateLead($request));

        return response()->json($lead->fresh()->load(['stage', 'assignedTo', 'assignedTeam', 'convertedCustomer']));
    }

    public function destroy(Lead $lead): JsonResponse
    {
        $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully.']);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls'],
        ]);

        $rows = $this->parseTabularFile($request->file('file'));
        $defaultStageId = LeadStage::default()->value('id') ?? LeadStage::query()->orderBy('stage_order')->value('id');
        $imported = 0;

        foreach ($rows as $row) {
            if (empty($row['name']) && empty($row['company_name'])) {
                continue;
            }

            Lead::updateOrCreate(
                ['email' => $row['email'] ?: null, 'phone' => $row['phone'] ?: null],
                [
                    'name' => $row['name'] ?? $row['company_name'] ?? 'Unnamed Lead',
                    'company_name' => $row['company_name'] ?? null,
                    'phone' => $row['phone'] ?? null,
                    'address' => $row['address'] ?? null,
                    'city' => $row['city'] ?? null,
                    'state' => $row['state'] ?? null,
                    'country' => $row['country'] ?? 'India',
                    'pincode' => $row['pincode'] ?? null,
                    'lead_source' => $row['lead_source'] ?? 'Import',
                    'lead_stage_id' => $row['lead_stage_id'] ?? $defaultStageId,
                    'assigned_to' => $row['assigned_to'] ?? null,
                    'assigned_team_id' => $row['assigned_team_id'] ?? null,
                    'notes' => $row['notes'] ?? null,
                    'created_by' => auth()->id(),
                ]
            );

            $imported++;
        }

        return response()->json(['message' => 'Leads imported successfully.', 'imported' => $imported]);
    }

    public function export()
    {
        $rows = Lead::with(['stage', 'assignedTo', 'assignedTeam'])
            ->orderBy('name')
            ->get()
            ->map(fn (Lead $lead) => [
                'name' => $lead->name,
                'company_name' => $lead->company_name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'city' => $lead->city,
                'state' => $lead->state,
                'country' => $lead->country,
                'lead_source' => $lead->lead_source,
                'stage' => $lead->stage?->name,
                'assigned_to' => $lead->assignedTo?->name,
                'assigned_team' => $lead->assignedTeam?->name,
                'is_converted' => $lead->is_converted ? 'Yes' : 'No',
            ]);

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Company', 'Email', 'Phone', 'City', 'State', 'Country', 'Source', 'Stage', 'Assigned To', 'Assigned Team', 'Converted']);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 'leads-export.csv');
    }

    public function convert(Lead $lead): JsonResponse
    {
        $converted = DB::transaction(function () use ($lead) {
            $customer = Customer::create([
                'name' => $lead->name,
                'company_name' => $lead->company_name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'address' => $lead->address,
                'city' => $lead->city,
                'state' => $lead->state,
                'country' => $lead->country,
                'pincode' => $lead->pincode,
                'customer_type' => 'retail',
                'status' => 'Active',
            ]);

            $lead->update([
                'is_converted' => true,
                'converted_customer_id' => $customer->id,
            ]);

            return $lead->fresh()->load('convertedCustomer');
        });

        return response()->json($converted);
    }

    public function sendEmail(Request $request, Lead $lead): JsonResponse
    {
        abort_if(blank($lead->email), 422, 'Lead email is required to send an email.');

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $log = EmailLog::create([
            'lead_id' => $lead->id,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'sent_to' => $lead->email,
            'status' => 'Pending',
            'created_by' => auth()->id(),
        ]);

        try {
            Mail::raw($data['body'], function ($message) use ($lead, $data) {
                $message->to($lead->email)->subject($data['subject']);
            });

            $log->update(['status' => 'Sent', 'sent_at' => now()]);
        } catch (\Throwable $exception) {
            $log->update(['status' => 'Failed', 'error_message' => $exception->getMessage()]);
        }

        return response()->json($log->fresh());
    }

    public function sendWhatsApp(Request $request, Lead $lead): JsonResponse
    {
        abort_if(blank($lead->phone), 422, 'Lead phone number is required to send a WhatsApp message.');

        $data = $request->validate([
            'template_id' => ['required', 'exists:whats_app_templates,id'],
            'message_body' => ['nullable', 'string'],
        ]);

        $template = WhatsAppTemplate::findOrFail($data['template_id']);

        $message = WhatsAppMessage::create([
            'template_id' => $template->id,
            'lead_id' => $lead->id,
            'phone' => $lead->phone,
            'message_body' => $data['message_body'] ?? $template->template_name,
            'status' => 'Queued',
            'created_by' => auth()->id(),
        ]);

        return response()->json($message->load('template'), 201);
    }

    protected function validateLead(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:15'],
            'lead_source' => ['nullable', 'string', 'max:255'],
            'lead_stage_id' => ['required', 'exists:lead_stages,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'assigned_team_id' => ['nullable', 'exists:sales_teams,id'],
            'notes' => ['nullable', 'string'],
            'is_converted' => ['nullable', 'boolean'],
            'converted_customer_id' => ['nullable', 'exists:customers,id'],
        ]);
    }

    protected function parseTabularFile(UploadedFile $file): Collection
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['xlsx', 'xls'], true)) {
            if (! class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                throw ValidationException::withMessages([
                    'file' => 'Excel imports require the phpoffice/phpspreadsheet package. CSV imports work immediately.',
                ]);
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $headerRow = array_shift($rows) ?? [];
            $header = collect($headerRow)
                ->mapWithKeys(fn ($value, $key) => [$key => strtolower(trim((string) $value))])
                ->all();

            return collect($rows)->map(function (array $row) use ($header) {
                $mapped = [];
                foreach ($row as $key => $value) {
                    $mapped[$header[$key] ?? strtolower((string) $key)] = $value;
                }

                return $mapped;
            });
        }

        $lines = array_filter(array_map('trim', file($file->getRealPath())));
        $header = null;

        return collect($lines)->map(function (string $line) use (&$header) {
            $row = str_getcsv($line);
            if ($header === null) {
                $header = array_map(fn ($value) => strtolower(trim($value)), $row);
                return null;
            }

            return array_combine($header, array_pad($row, count($header), null));
        })->filter();
    }
}
