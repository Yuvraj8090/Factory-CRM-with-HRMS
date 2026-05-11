<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="Finance Workspace" :title="$invoice->invoice_number" description="Invoice detail with customer, payment exposure, and line-level billing context." :back-url="route('finance.invoices.index')" back-label="Back to Invoices">
            <a href="{{ route('finance.invoices.edit', $invoice) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Edit Invoice</a>
        </x-crud.page-header>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.9fr)]">
            <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Customer</p><p class="mt-2 text-lg font-semibold text-slate-950">{{ $invoice->customer?->name ?: 'Unknown customer' }}</p></div>
                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Invoice Date</p><p class="mt-2 text-sm text-slate-700">{{ optional($invoice->invoice_date)->format('d M Y') }}</p></div>
                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Invoice Status</p><div class="mt-2"><x-crud.status-badge :value="$invoice->invoice_status" /></div></div>
                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Payment Status</p><div class="mt-2"><x-crud.status-badge :value="$invoice->payment_status" /></div></div>
                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Due Date</p><p class="mt-2 text-sm text-slate-700">{{ optional($invoice->due_date)->format('d M Y') ?: 'Not set' }}</p></div>
                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Quotation</p><p class="mt-2 text-sm text-slate-700">{{ $invoice->quotation?->quotation_number ?: 'No quotation linked' }}</p></div>
                </div>
            </section>
            <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
                <h2 class="text-lg font-bold text-slate-950">Financial Summary</h2>
                <div class="mt-5 grid gap-4">
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Subtotal</p><p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $invoice->subtotal, 2) }}</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Tax</p><p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $invoice->tax_amount, 2) }}</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Total</p><p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $invoice->total, 2) }}</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Balance Due</p><p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $invoice->balance_due, 2) }}</p></div>
                </div>
            </section>
        </div>

        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <h2 class="text-lg font-bold text-slate-950">Line Items</h2>
            <div class="mt-5 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-4 py-3">Item</th><th class="px-4 py-3">Qty</th><th class="px-4 py-3">Unit Price</th><th class="px-4 py-3">GST %</th><th class="px-4 py-3">Tax</th><th class="px-4 py-3">Total</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-slate-700">{{ $item->description ?: $item->item?->item_name ?: 'Custom item' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ number_format((float) $item->quantity, 2) }} {{ $item->unit }}</td>
                                <td class="px-4 py-3 text-slate-600">₹{{ number_format((float) $item->unit_price, 2) }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ number_format((float) $item->gst_rate, 2) }}</td>
                                <td class="px-4 py-3 text-slate-600">₹{{ number_format((float) $item->tax_amount, 2) }}</td>
                                <td class="px-4 py-3 text-slate-600">₹{{ number_format((float) $item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
