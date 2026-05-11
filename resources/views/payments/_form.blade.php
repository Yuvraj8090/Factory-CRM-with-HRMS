@php($payment = $payment ?? null)

<div class="space-y-6">
    <section class="app-card app-card-body">
        <div class="border-b border-slate-200 pb-5">
            <h2 class="text-lg font-bold text-slate-950">Payment Receipt</h2>
            <p class="mt-1 text-sm text-slate-500">Allocate collections to invoices with clean payment metadata and references.</p>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><x-input-label for="payment_number" value="Payment Number" /><x-text-input id="payment_number" name="payment_number" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('payment_number', $payment?->payment_number)" required /><x-input-error :messages="$errors->get('payment_number')" class="mt-2" /></div>
            <div><x-input-label for="payment_date" value="Payment Date" /><x-text-input id="payment_date" name="payment_date" type="date" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('payment_date', optional($payment?->payment_date)->format('Y-m-d') ?? now()->format('Y-m-d'))" required /><x-input-error :messages="$errors->get('payment_date')" class="mt-2" /></div>
            <div><x-input-label for="customer_id" value="Customer" /><select id="customer_id" name="customer_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required><option value="">Select customer</option>@foreach ($customers as $customer)<option value="{{ $customer->id }}" @selected(old('customer_id', $payment?->customer_id) == $customer->id)>{{ $customer->name }}{{ $customer->company_name ? ' • '.$customer->company_name : '' }}</option>@endforeach</select><x-input-error :messages="$errors->get('customer_id')" class="mt-2" /></div>
            <div><x-input-label for="invoice_id" value="Invoice" /><select id="invoice_id" name="invoice_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required><option value="">Select invoice</option>@foreach ($invoices as $invoice)<option value="{{ $invoice->id }}" @selected(old('invoice_id', $payment?->invoice_id) == $invoice->id)>{{ $invoice->invoice_number }} • Due ₹{{ number_format((float) $invoice->balance_due, 2) }}</option>@endforeach</select><x-input-error :messages="$errors->get('invoice_id')" class="mt-2" /></div>
            <div><x-input-label for="amount" value="Amount" /><x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('amount', $payment?->amount)" required /><x-input-error :messages="$errors->get('amount')" class="mt-2" /></div>
            <div><x-input-label for="payment_method" value="Payment Method" /><select id="payment_method" name="payment_method" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm" required>@foreach ($paymentMethods ?? $payment_methods as $method)<option value="{{ $method }}" @selected(old('payment_method', $payment?->payment_method ?? 'Bank Transfer') === $method)>{{ $method }}</option>@endforeach</select><x-input-error :messages="$errors->get('payment_method')" class="mt-2" /></div>
            <div><x-input-label for="reference_number" value="Reference Number" /><x-text-input id="reference_number" name="reference_number" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="old('reference_number', $payment?->reference_number)" /><x-input-error :messages="$errors->get('reference_number')" class="mt-2" /></div>
            <div class="lg:col-span-2"><x-input-label for="notes" value="Notes" /><textarea id="notes" name="notes" rows="4" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm">{{ old('notes', $payment?->notes) }}</textarea><x-input-error :messages="$errors->get('notes')" class="mt-2" /></div>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('finance.payments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $payment ? 'Update Payment' : 'Save Payment' }}</button>
    </div>
</div>
