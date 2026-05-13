<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsDataTables;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ItemMaster;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    use BuildsDataTables;

    public function __construct()
    {
        $this->authorizeResource(Invoice::class, 'invoice');
    }

    public function index(Request $request): JsonResponse|View
    {
        $query = Invoice::with(['customer', 'quotation', 'items.item', 'payments', 'creator'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($invoiceQuery) use ($search) {
                    $invoiceQuery
                        ->where('invoice_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($customerQuery) => $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('company_name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->string('payment_status')))
            ->when($request->filled('invoice_status'), fn ($query) => $query->where('invoice_status', $request->string('invoice_status')))
            ->latest('invoice_date');

        if ($this->isDataTableRequest($request)) {
            return $this->dataTable($query)
                ->editColumn('invoice_number', fn (Invoice $invoice) => $this->recordLink(
                    $invoice->invoice_number,
                    route('finance.invoices.show', $invoice),
                    [optional($invoice->invoice_date)->format('d M Y')]
                ))
                ->addColumn('customer_name', fn (Invoice $invoice) => e($invoice->customer?->name ?: 'Unknown customer'))
                ->addColumn('due_date_display', fn (Invoice $invoice) => e(optional($invoice->due_date)->format('d M Y') ?: 'Not set'))
                ->addColumn('invoice_status_badge', fn (Invoice $invoice) => $this->statusBadge($invoice->invoice_status))
                ->addColumn('payment_status_badge', fn (Invoice $invoice) => $this->statusBadge($invoice->payment_status))
                ->addColumn('total_display', fn (Invoice $invoice) => e($this->money((float) $invoice->total)))
                ->addColumn('actions', fn (Invoice $invoice) => $this->actionButtons(route('finance.invoices.show', $invoice), route('finance.invoices.edit', $invoice)))
                ->rawColumns(['invoice_number', 'invoice_status_badge', 'payment_status_badge', 'actions'])
                ->toJson();
        }

        $invoices = (clone $query)->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('invoices.index', [
                'invoices' => $invoices,
                'paymentStatuses' => ['Pending', 'Partial', 'Paid'],
                'invoiceStatuses' => ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'],
            ]);
        }

        return response()->json($invoices);
    }

    public function create(Request $request): JsonResponse|View
    {
        $data = [
            'customers' => Customer::query()->select('id', 'name', 'company_name', 'state')->orderBy('name')->get(),
            'quotations' => Quotation::query()->select('id', 'quotation_number', 'customer_id')->orderByDesc('quotation_date')->get(),
            'items' => ItemMaster::active()->orderBy('item_name')->get(),
            'paymentStatuses' => ['Pending', 'Partial', 'Paid'],
            'payment_statuses' => ['Pending', 'Partial', 'Paid'],
            'invoiceStatuses' => ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'],
            'invoice_statuses' => ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'],
        ];

        if (! $request->expectsJson()) {
            return view('invoices.create', $data);
        }

        return response()->json($data);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validateInvoice($request);

        $invoice = DB::transaction(function () use ($data) {
            $customer = Customer::findOrFail($data['customer_id']);
            $totals = $this->calculateTotals($customer, $data);

            $invoice = Invoice::create(array_merge($totals, [
                'invoice_number' => $data['invoice_number'],
                'invoice_date' => $data['invoice_date'],
                'customer_id' => $customer->id,
                'quotation_id' => $data['quotation_id'] ?? null,
                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? 0,
                'payment_status' => $totals['payment_status'],
                'invoice_status' => $data['invoice_status'],
                'due_date' => $data['due_date'] ?? null,
                'notes' => $data['notes'] ?? null,
                'terms_conditions' => $data['terms_conditions'] ?? null,
                'created_by' => auth()->id(),
            ]));

            foreach ($totals['items'] as $line) {
                $invoice->items()->create($line);
            }

            return $invoice->load(['customer', 'quotation', 'items.item', 'payments', 'creator']);
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.invoices.show', $invoice)
                ->with('status', 'Invoice created successfully.');
        }

        return response()->json($invoice, 201);
    }

    public function show(Request $request, Invoice $invoice): JsonResponse|View
    {
        $invoice->load(['customer', 'quotation', 'items.item', 'payments', 'debitNotes', 'creator']);

        if (! $request->expectsJson()) {
            return view('invoices.show', compact('invoice'));
        }

        return response()->json($invoice);
    }

    public function edit(Request $request, Invoice $invoice): JsonResponse|View
    {
        $data = [
            'invoice' => $invoice->load(['customer', 'quotation', 'items.item', 'payments', 'creator']),
            'customers' => Customer::query()->select('id', 'name', 'company_name', 'state')->orderBy('name')->get(),
            'quotations' => Quotation::query()->select('id', 'quotation_number', 'customer_id')->orderByDesc('quotation_date')->get(),
            'items' => ItemMaster::active()->orderBy('item_name')->get(),
            'paymentStatuses' => ['Pending', 'Partial', 'Paid'],
            'payment_statuses' => ['Pending', 'Partial', 'Paid'],
            'invoiceStatuses' => ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'],
            'invoice_statuses' => ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'],
        ];

        if (! $request->expectsJson()) {
            return view('invoices.edit', $data);
        }

        return response()->json($data);
    }

    public function update(Request $request, Invoice $invoice): JsonResponse|RedirectResponse
    {
        $data = $this->validateInvoice($request, $invoice->id);

        $invoice = DB::transaction(function () use ($invoice, $data) {
            $customer = Customer::findOrFail($data['customer_id']);
            $totals = $this->calculateTotals($customer, $data, (float) $invoice->paid_amount);

            $invoice->update(array_merge($totals, [
                'invoice_number' => $data['invoice_number'],
                'invoice_date' => $data['invoice_date'],
                'customer_id' => $customer->id,
                'quotation_id' => $data['quotation_id'] ?? null,
                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? 0,
                'payment_status' => $totals['payment_status'],
                'invoice_status' => $data['invoice_status'],
                'due_date' => $data['due_date'] ?? null,
                'notes' => $data['notes'] ?? null,
                'terms_conditions' => $data['terms_conditions'] ?? null,
            ]));

            $invoice->items()->delete();
            foreach ($totals['items'] as $line) {
                $invoice->items()->create($line);
            }

            return $invoice->fresh()->load(['customer', 'quotation', 'items.item', 'payments', 'creator']);
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.invoices.show', $invoice)
                ->with('status', 'Invoice updated successfully.');
        }

        return response()->json($invoice);
    }

    public function destroy(Request $request, Invoice $invoice): JsonResponse|RedirectResponse
    {
        $invoice->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.invoices.index')
                ->with('status', 'Invoice deleted successfully.');
        }

        return response()->json(['message' => 'Invoice deleted successfully.']);
    }

    public function fromQuotation(Request $request, Quotation $quotation): JsonResponse|RedirectResponse
    {
        $data = [
            'quotation' => $quotation->load(['customer', 'items.item']),
            'suggested_invoice_number' => 'INV-' . now()->format('YmdHis'),
        ];

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.invoices.create', [
                    'quotation_id' => $quotation->id,
                    'invoice_number' => $data['suggested_invoice_number'],
                ]);
        }

        return response()->json($data);
    }

    protected function validateInvoice(Request $request, ?int $invoiceId = null): array
    {
        return $request->validate([
            'invoice_number' => ['required', 'string', 'max:255', 'unique:invoices,invoice_number,' . $invoiceId],
            'invoice_date' => ['required', 'date'],
            'customer_id' => ['required', 'exists:customers,id'],
            'quotation_id' => ['nullable', 'exists:quotations,id'],
            'discount_type' => ['nullable', 'in:percentage,fixed'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'invoice_status' => ['required', 'in:Draft,Sent,Paid,Overdue,Cancelled'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['nullable', 'exists:item_masters,id'],
            'items.*.hsn_code' => ['nullable', 'string', 'max:20'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['nullable', 'string', 'max:20'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.gst_rate' => ['nullable', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
        ]);
    }

    protected function calculateTotals(Customer $customer, array $data, float $paidAmount = 0): array
    {
        $companyState = config('app.company_state', env('COMPANY_STATE', $customer->state));
        $sameState = filled($customer->state) && filled($companyState) && strtolower($customer->state) === strtolower($companyState);

        $subtotal = 0;
        $taxAmount = 0;
        $items = [];

        foreach ($data['items'] as $itemData) {
            $quantity = (float) $itemData['quantity'];
            $unitPrice = (float) $itemData['unit_price'];
            $gstRate = (float) ($itemData['gst_rate'] ?? 0);
            $lineSubtotal = $quantity * $unitPrice;
            $lineTax = $lineSubtotal * ($gstRate / 100);

            $subtotal += $lineSubtotal;
            $taxAmount += $lineTax;

            $items[] = [
                'item_id' => $itemData['item_id'] ?? null,
                'hsn_code' => $itemData['hsn_code'] ?? null,
                'quantity' => $quantity,
                'unit' => $itemData['unit'] ?? null,
                'unit_price' => round($unitPrice, 2),
                'gst_rate' => round($gstRate, 2),
                'cgst_rate' => $sameState ? round($gstRate / 2, 2) : 0,
                'sgst_rate' => $sameState ? round($gstRate / 2, 2) : 0,
                'igst_rate' => $sameState ? 0 : round($gstRate, 2),
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

        $total = round(($subtotal - $discountAmount) + $taxAmount, 2);
        $balanceDue = max($total - $paidAmount, 0);

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => $total,
            'paid_amount' => round($paidAmount, 2),
            'balance_due' => round($balanceDue, 2),
            'payment_status' => $balanceDue <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Pending'),
            'items' => $items,
        ];
    }
}
