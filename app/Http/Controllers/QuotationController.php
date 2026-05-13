<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsDataTables;
use App\Models\Customer;
use App\Models\ItemMaster;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuotationController extends Controller
{
    use BuildsDataTables;

    public function index(Request $request): JsonResponse|View
    {
        $statuses = ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired'];

        $query = Quotation::with(['customer', 'items.item', 'creator'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('quotation_date');

        if ($this->isDataTableRequest($request)) {
            return $this->dataTable($query)
                ->editColumn('quotation_number', fn (Quotation $quotation) => $this->recordLink(
                    $quotation->quotation_number,
                    route('finance.quotations.show', $quotation),
                    [optional($quotation->valid_until)->format('d M Y') ? 'Valid until ' . optional($quotation->valid_until)->format('d M Y') : 'No validity date set']
                ))
                ->addColumn('customer_name', fn (Quotation $quotation) => e($quotation->customer?->name ?: 'Unknown customer'))
                ->addColumn('quotation_date_display', fn (Quotation $quotation) => e(optional($quotation->quotation_date)->format('d M Y')))
                ->addColumn('status_badge', fn (Quotation $quotation) => $this->statusBadge($quotation->status))
                ->addColumn('total_display', fn (Quotation $quotation) => e($this->money((float) $quotation->total)))
                ->addColumn('actions', fn (Quotation $quotation) => $this->actionButtons(route('finance.quotations.show', $quotation), route('finance.quotations.edit', $quotation)))
                ->rawColumns(['quotation_number', 'status_badge', 'actions'])
                ->toJson();
        }

        $quotations = (clone $query)->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('quotations.index', compact('quotations', 'statuses'));
        }

        return response()->json($quotations);
    }

    public function create(Request $request): JsonResponse|View
    {
        $payload = [
            'customers' => Customer::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'items' => ItemMaster::active()->orderBy('item_name')->get(),
            'statuses' => ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired'],
        ];

        if (! $request->expectsJson()) {
            return view('quotations.create', $payload);
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validateQuotation($request);

        $quotation = DB::transaction(function () use ($data) {
            $totals = $this->calculateTotals($data);

            $quotation = Quotation::create(array_merge($totals, [
                'quotation_number' => $data['quotation_number'],
                'customer_id' => $data['customer_id'],
                'quotation_date' => $data['quotation_date'],
                'valid_until' => $data['valid_until'] ?? null,
                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'terms_conditions' => $data['terms_conditions'] ?? null,
                'status' => $data['status'],
                'created_by' => auth()->id(),
            ]));

            foreach ($totals['items'] as $line) {
                $quotation->items()->create($line);
            }

            return $quotation->load(['customer', 'items.item', 'creator']);
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.quotations.show', $quotation)
                ->with('status', 'Quotation created successfully.');
        }

        return response()->json($quotation, 201);
    }

    public function show(Request $request, Quotation $quotation): JsonResponse|View
    {
        $quotation->load(['customer', 'items.item', 'creator', 'invoices']);

        if (! $request->expectsJson()) {
            return view('quotations.show', compact('quotation'));
        }

        return response()->json($quotation);
    }

    public function edit(Request $request, Quotation $quotation): JsonResponse|View
    {
        $payload = [
            'quotation' => $quotation->load(['customer', 'items.item', 'creator']),
            'customers' => Customer::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'items' => ItemMaster::active()->orderBy('item_name')->get(),
            'statuses' => ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired'],
        ];

        if (! $request->expectsJson()) {
            return view('quotations.edit', $payload);
        }

        return response()->json($payload);
    }

    public function update(Request $request, Quotation $quotation): JsonResponse|RedirectResponse
    {
        $data = $this->validateQuotation($request, $quotation->id);

        $quotation = DB::transaction(function () use ($quotation, $data) {
            $totals = $this->calculateTotals($data);

            $quotation->update(array_merge($totals, [
                'quotation_number' => $data['quotation_number'],
                'customer_id' => $data['customer_id'],
                'quotation_date' => $data['quotation_date'],
                'valid_until' => $data['valid_until'] ?? null,
                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'terms_conditions' => $data['terms_conditions'] ?? null,
                'status' => $data['status'],
            ]));

            $quotation->items()->delete();
            foreach ($totals['items'] as $line) {
                $quotation->items()->create($line);
            }

            return $quotation->fresh()->load(['customer', 'items.item', 'creator']);
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.quotations.show', $quotation)
                ->with('status', 'Quotation updated successfully.');
        }

        return response()->json($quotation);
    }

    public function destroy(Request $request, Quotation $quotation): JsonResponse|RedirectResponse
    {
        $quotation->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.quotations.index')
                ->with('status', 'Quotation deleted successfully.');
        }

        return response()->json(['message' => 'Quotation deleted successfully.']);
    }

    protected function validateQuotation(Request $request, ?int $quotationId = null): array
    {
        return $request->validate([
            'quotation_number' => ['required', 'string', 'max:255', 'unique:quotations,quotation_number,' . $quotationId],
            'customer_id' => ['required', 'exists:customers,id'],
            'quotation_date' => ['required', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:quotation_date'],
            'discount_type' => ['nullable', 'in:percentage,fixed'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
            'status' => ['required', 'in:Draft,Sent,Accepted,Rejected,Expired'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['nullable', 'exists:item_masters,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
        ]);
    }

    protected function calculateTotals(array $data): array
    {
        $subtotal = 0;
        $taxAmount = 0;
        $items = [];

        foreach ($data['items'] as $itemData) {
            $quantity = (float) $itemData['quantity'];
            $unitPrice = (float) $itemData['unit_price'];
            $taxRate = (float) ($itemData['tax_rate'] ?? 0);
            $lineSubtotal = $quantity * $unitPrice;
            $lineTax = $lineSubtotal * ($taxRate / 100);

            $subtotal += $lineSubtotal;
            $taxAmount += $lineTax;

            $items[] = [
                'item_id' => $itemData['item_id'] ?? null,
                'quantity' => $quantity,
                'unit_price' => round($unitPrice, 2),
                'tax_rate' => round($taxRate, 2),
                'tax_amount' => round($lineTax, 2),
                'total' => round($lineSubtotal + $lineTax, 2),
                'description' => $itemData['description'] ?? null,
            ];
        }

        $discountAmount = 0;
        $discountValue = (float) ($data['discount_value'] ?? 0);
        if (($data['discount_type'] ?? null) === 'percentage') {
            $discountAmount = $subtotal * ($discountValue / 100);
        } elseif (($data['discount_type'] ?? null) === 'fixed') {
            $discountAmount = $discountValue;
        }

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => round(($subtotal - $discountAmount) + $taxAmount, 2),
            'items' => $items,
        ];
    }
}
