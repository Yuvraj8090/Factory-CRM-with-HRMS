<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ItemMaster;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $invoices = Invoice::with(['customer', 'quotation', 'items.item', 'payments', 'creator'])
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->string('payment_status')))
            ->when($request->filled('invoice_status'), fn ($query) => $query->where('invoice_status', $request->string('invoice_status')))
            ->latest('invoice_date')
            ->paginate($request->integer('per_page', 15));

        return response()->json($invoices);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'customers' => Customer::query()->select('id', 'name', 'company_name', 'state')->orderBy('name')->get(),
            'quotations' => Quotation::query()->select('id', 'quotation_number', 'customer_id')->orderByDesc('quotation_date')->get(),
            'items' => ItemMaster::active()->orderBy('item_name')->get(),
            'payment_statuses' => ['Pending', 'Partial', 'Paid'],
            'invoice_statuses' => ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'],
        ]);
    }

    public function store(Request $request): JsonResponse
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

        return response()->json($invoice, 201);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json($invoice->load(['customer', 'quotation', 'items.item', 'payments', 'debitNotes', 'creator']));
    }

    public function edit(Invoice $invoice): JsonResponse
    {
        return response()->json([
            'invoice' => $invoice->load(['customer', 'quotation', 'items.item', 'payments', 'creator']),
            'customers' => Customer::query()->select('id', 'name', 'company_name', 'state')->orderBy('name')->get(),
            'quotations' => Quotation::query()->select('id', 'quotation_number', 'customer_id')->orderByDesc('quotation_date')->get(),
            'items' => ItemMaster::active()->orderBy('item_name')->get(),
            'payment_statuses' => ['Pending', 'Partial', 'Paid'],
            'invoice_statuses' => ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'],
        ]);
    }

    public function update(Request $request, Invoice $invoice): JsonResponse
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

        return response()->json($invoice);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $invoice->delete();

        return response()->json(['message' => 'Invoice deleted successfully.']);
    }

    public function fromQuotation(Quotation $quotation): JsonResponse
    {
        return response()->json([
            'quotation' => $quotation->load(['customer', 'items.item']),
            'suggested_invoice_number' => 'INV-' . now()->format('YmdHis'),
        ]);
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
