<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $payments = Payment::with(['invoice', 'customer', 'creator'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($paymentQuery) use ($search) {
                    $paymentQuery
                        ->where('payment_number', 'like', "%{$search}%")
                        ->orWhere('reference_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($customerQuery) => $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('company_name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('customer_id'), fn ($query) => $query->where('customer_id', $request->integer('customer_id')))
            ->when($request->filled('payment_method'), fn ($query) => $query->where('payment_method', $request->string('payment_method')))
            ->latest('payment_date')
            ->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('payments.index', [
                'payments' => $payments,
                'customers' => Customer::query()->select('id', 'name')->orderBy('name')->get(),
                'paymentMethods' => ['Cash', 'Cheque', 'Bank Transfer', 'UPI', 'Credit Card', 'Debit Card', 'Wallet'],
            ]);
        }

        return response()->json($payments);
    }

    public function create(Request $request): JsonResponse|View
    {
        $data = [
            'customers' => Customer::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'invoices' => Invoice::open()->select('id', 'invoice_number', 'customer_id', 'balance_due')->orderByDesc('invoice_date')->get(),
            'paymentMethods' => ['Cash', 'Cheque', 'Bank Transfer', 'UPI', 'Credit Card', 'Debit Card', 'Wallet'],
            'payment_methods' => ['Cash', 'Cheque', 'Bank Transfer', 'UPI', 'Credit Card', 'Debit Card', 'Wallet'],
        ];

        if (! $request->expectsJson()) {
            return view('payments.create', $data);
        }

        return response()->json($data);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validatePayment($request);

        $payment = DB::transaction(function () use ($data) {
            $payment = Payment::create(array_merge($data, [
                'created_by' => auth()->id(),
            ]));

            $this->refreshInvoiceBalance(Invoice::findOrFail($data['invoice_id']));

            return $payment->load(['invoice', 'customer', 'creator']);
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.payments.show', $payment)
                ->with('status', 'Payment captured successfully.');
        }

        return response()->json($payment, 201);
    }

    public function show(Request $request, Payment $payment): JsonResponse|View
    {
        $payment->load(['invoice', 'customer', 'creator']);

        if (! $request->expectsJson()) {
            return view('payments.show', compact('payment'));
        }

        return response()->json($payment);
    }

    public function edit(Request $request, Payment $payment): JsonResponse|View
    {
        $data = [
            'payment' => $payment->load(['invoice', 'customer', 'creator']),
            'customers' => Customer::query()->select('id', 'name', 'company_name')->orderBy('name')->get(),
            'invoices' => Invoice::query()->select('id', 'invoice_number', 'customer_id', 'balance_due')->orderByDesc('invoice_date')->get(),
            'paymentMethods' => ['Cash', 'Cheque', 'Bank Transfer', 'UPI', 'Credit Card', 'Debit Card', 'Wallet'],
            'payment_methods' => ['Cash', 'Cheque', 'Bank Transfer', 'UPI', 'Credit Card', 'Debit Card', 'Wallet'],
        ];

        if (! $request->expectsJson()) {
            return view('payments.edit', $data);
        }

        return response()->json($data);
    }

    public function update(Request $request, Payment $payment): JsonResponse|RedirectResponse
    {
        $data = $this->validatePayment($request, $payment->id);

        $payment = DB::transaction(function () use ($payment, $data) {
            $oldInvoiceId = $payment->invoice_id;
            $payment->update($data);

            $this->refreshInvoiceBalance(Invoice::findOrFail($data['invoice_id']));
            if ($oldInvoiceId !== (int) $data['invoice_id']) {
                $this->refreshInvoiceBalance(Invoice::findOrFail($oldInvoiceId));
            }

            return $payment->fresh()->load(['invoice', 'customer', 'creator']);
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.payments.show', $payment)
                ->with('status', 'Payment updated successfully.');
        }

        return response()->json($payment);
    }

    public function destroy(Request $request, Payment $payment): JsonResponse|RedirectResponse
    {
        DB::transaction(function () use ($payment) {
            $invoiceId = $payment->invoice_id;
            $payment->delete();
            $this->refreshInvoiceBalance(Invoice::findOrFail($invoiceId));
        });

        if (! $request->expectsJson()) {
            return redirect()
                ->route('finance.payments.index')
                ->with('status', 'Payment deleted successfully.');
        }

        return response()->json(['message' => 'Payment deleted successfully.']);
    }

    protected function validatePayment(Request $request, ?int $paymentId = null): array
    {
        return $request->validate([
            'payment_number' => ['required', 'string', 'max:255', 'unique:payments,payment_number,' . $paymentId],
            'payment_date' => ['required', 'date'],
            'invoice_id' => ['required', 'exists:invoices,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:Cash,Cheque,Bank Transfer,UPI,Credit Card,Debit Card,Wallet'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    protected function refreshInvoiceBalance(Invoice $invoice): void
    {
        $paid = (float) $invoice->payments()->sum('amount');
        $balance = max((float) $invoice->total - $paid, 0);

        $invoice->update([
            'paid_amount' => round($paid, 2),
            'balance_due' => round($balance, 2),
            'payment_status' => $balance <= 0 ? 'Paid' : ($paid > 0 ? 'Partial' : 'Pending'),
            'invoice_status' => $balance <= 0 ? 'Paid' : $invoice->invoice_status,
        ]);
    }
}
