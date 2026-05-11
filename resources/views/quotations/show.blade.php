<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$quotation->quotation_number" description="Quotation detail with customer context, itemized commercial terms, and conversion readiness." icon="document" :breadcrumbs="[['label' => 'Finance'], ['label' => 'Quotations', 'url' => route('finance.quotations.index')], ['label' => 'Details']]" :back-url="route('finance.quotations.index')" back-label="Back to Quotations" :action-url="route('finance.quotations.edit', $quotation)" action-label="Edit Quotation" />
    </x-slot>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_380px]">
        <section class="app-card app-card-body">
            <div class="grid gap-6 md:grid-cols-2">
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Customer</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $quotation->customer?->name ?: 'Unknown customer' }}</p></div>
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$quotation->status" /></div></div>
            </div>
            <div class="mt-8 overflow-x-auto">
                <table class="table table-hover app-data-table text-sm">
                    <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-4 py-3">Item</th><th class="px-4 py-3">Qty</th><th class="px-4 py-3">Rate</th><th class="px-4 py-3">Tax</th><th class="px-4 py-3 text-right">Total</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($quotation->items as $item)
                            <tr><td class="px-4 py-3">{{ $item->item?->item_name ?: ($item->description ?: 'Ad hoc line item') }}</td><td class="px-4 py-3">{{ number_format((float) $item->quantity, 2) }}</td><td class="px-4 py-3">₹{{ number_format((float) $item->unit_price, 2) }}</td><td class="px-4 py-3">{{ number_format((float) $item->tax_rate, 2) }}%</td><td class="px-4 py-3 text-right">₹{{ number_format((float) $item->total, 2) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <section class="app-card app-card-body">
            <h2 class="text-lg font-bold text-slate-950">Financial Summary</h2>
            <dl class="mt-6 space-y-4 text-sm text-slate-700">
                <div class="flex items-center justify-between"><dt>Subtotal</dt><dd class="font-semibold text-slate-950">₹{{ number_format((float) $quotation->subtotal, 2) }}</dd></div>
                <div class="flex items-center justify-between"><dt>Discount</dt><dd class="font-semibold text-slate-950">₹{{ number_format((float) $quotation->discount_amount, 2) }}</dd></div>
                <div class="flex items-center justify-between"><dt>Tax</dt><dd class="font-semibold text-slate-950">₹{{ number_format((float) $quotation->tax_amount, 2) }}</dd></div>
                <div class="flex items-center justify-between border-t border-slate-200 pt-4"><dt>Total</dt><dd class="text-lg font-bold text-slate-950">₹{{ number_format((float) $quotation->total, 2) }}</dd></div>
            </dl>
        </section>
    </div>
</x-app-layout>
