<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="Finance Workspace" :title="$payment->payment_number" description="Payment detail with invoice linkage, settlement amount, and source references." :back-url="route('finance.payments.index')" back-label="Back to Payments">
            <a href="{{ route('finance.payments.edit', $payment) }}" class="btn btn-primary">Edit Payment</a>
        </x-crud.page-header>
    </x-slot>
    <section class="app-card app-card-body">
        <div class="grid gap-6 lg:grid-cols-2">
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Customer</p><p class="mt-2 text-lg font-semibold text-slate-950">{{ $payment->customer?->name ?: 'Unknown customer' }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Invoice</p><p class="mt-2 text-sm text-slate-700">{{ $payment->invoice?->invoice_number ?: 'Unknown invoice' }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Payment Date</p><p class="mt-2 text-sm text-slate-700">{{ optional($payment->payment_date)->format('d M Y') }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Method</p><p class="mt-2 text-sm text-slate-700">{{ $payment->payment_method }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Amount</p><p class="mt-2 text-lg font-semibold text-slate-950">₹{{ number_format((float) $payment->amount, 2) }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Reference</p><p class="mt-2 text-sm text-slate-700">{{ $payment->reference_number ?: 'No reference' }}</p></div>
            <div class="lg:col-span-2"><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Notes</p><p class="mt-2 text-sm text-slate-700">{{ $payment->notes ?: 'No notes recorded.' }}</p></div>
        </div>
    </section>
</x-app-layout>
