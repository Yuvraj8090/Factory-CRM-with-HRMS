<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="Finance Workspace" title="Payments" description="Track invoice collections, payment channels, and cashflow references with a clear register." :action-url="route('finance.payments.create')" action-label="Add Payment" />
    </x-slot>

    <section class="rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
        <form method="GET" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_240px_240px_auto]">
            <div><x-input-label for="search" value="Search" /><x-text-input id="search" name="search" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="request('search')" placeholder="Payment, reference, or customer..." /></div>
            <div><x-input-label for="customer_id" value="Customer" /><select id="customer_id" name="customer_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All customers</option>@foreach ($customers as $customer)<option value="{{ $customer->id }}" @selected((string) request('customer_id') === (string) $customer->id)>{{ $customer->name }}</option>@endforeach</select></div>
            <div><x-input-label for="payment_method" value="Method" /><select id="payment_method" name="payment_method" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All methods</option>@foreach ($paymentMethods as $method)<option value="{{ $method }}" @selected(request('payment_method') === $method)>{{ $method }}</option>@endforeach</select></div>
            <div class="flex items-end"><button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Apply</button></div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Payment</th><th class="px-6 py-4">Customer</th><th class="px-6 py-4">Invoice</th><th class="px-6 py-4">Method</th><th class="px-6 py-4">Amount</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($payments as $payment)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('finance.payments.show', $payment) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $payment->payment_number }}</a><p class="mt-1 text-sm text-slate-500">{{ optional($payment->payment_date)->format('d M Y') }}</p><p class="mt-2 text-xs text-slate-500">{{ $payment->reference_number ?: 'No reference' }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ $payment->customer?->name ?: 'Unknown customer' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $payment->invoice?->invoice_number ?: 'Unknown invoice' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $payment->payment_method }}</td>
                            <td class="px-6 py-5 text-slate-600">₹{{ number_format((float) $payment->amount, 2) }}</td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('finance.payments.show', $payment) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View</a><a href="{{ route('finance.payments.edit', $payment) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No payments matched the current filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $payments->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
