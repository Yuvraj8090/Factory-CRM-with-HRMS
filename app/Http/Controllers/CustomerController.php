<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\EmailLog;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $customers = Customer::withCount(['quotations', 'invoices', 'payments'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('customer_type'), fn ($query) => $query->where('customer_type', $request->string('customer_type')))
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($customers);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'customer_types' => ['retail', 'wholesale', 'institutional'],
            'statuses' => ['Active', 'Inactive', 'Blocked'],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $customer = Customer::create($this->validateCustomer($request));

        return response()->json($customer, 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer->load(['quotations.items', 'invoices.items', 'payments', 'emailLogs', 'whatsAppMessages.template']));
    }

    public function edit(Customer $customer): JsonResponse
    {
        return response()->json([
            'customer' => $customer,
            'customer_types' => ['retail', 'wholesale', 'institutional'],
            'statuses' => ['Active', 'Inactive', 'Blocked'],
        ]);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $customer->update($this->validateCustomer($request, $customer->id));

        return response()->json($customer->fresh());
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully.']);
    }

    public function sendEmail(Request $request, Customer $customer): JsonResponse
    {
        abort_if(blank($customer->email), 422, 'Customer email is required to send an email.');

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $log = EmailLog::create([
            'customer_id' => $customer->id,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'sent_to' => $customer->email,
            'status' => 'Pending',
            'created_by' => auth()->id(),
        ]);

        try {
            Mail::raw($data['body'], function ($message) use ($customer, $data) {
                $message->to($customer->email)->subject($data['subject']);
            });

            $log->update(['status' => 'Sent', 'sent_at' => now()]);
        } catch (\Throwable $exception) {
            $log->update(['status' => 'Failed', 'error_message' => $exception->getMessage()]);
        }

        return response()->json($log->fresh());
    }

    public function sendWhatsApp(Request $request, Customer $customer): JsonResponse
    {
        abort_if(blank($customer->phone), 422, 'Customer phone number is required to send a WhatsApp message.');

        $data = $request->validate([
            'template_id' => ['required', 'exists:whats_app_templates,id'],
            'message_body' => ['nullable', 'string'],
        ]);

        $template = WhatsAppTemplate::findOrFail($data['template_id']);

        $message = WhatsAppMessage::create([
            'template_id' => $template->id,
            'customer_id' => $customer->id,
            'phone' => $customer->phone,
            'message_body' => $data['message_body'] ?? $template->template_name,
            'status' => 'Queued',
            'created_by' => auth()->id(),
        ]);

        return response()->json($message->load('template'), 201);
    }

    protected function validateCustomer(Request $request, ?int $customerId = null): array
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
            'gst_number' => ['nullable', 'string', 'max:20', 'unique:customers,gst_number,' . $customerId],
            'pan_number' => ['nullable', 'string', 'max:20', 'unique:customers,pan_number,' . $customerId],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'customer_type' => ['required', 'in:retail,wholesale,institutional'],
            'status' => ['required', 'string', 'max:50'],
        ]);
    }
}
