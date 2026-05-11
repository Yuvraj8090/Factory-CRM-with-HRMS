@php($debitNote = $debitNote ?? $debit_note)
<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$debitNote->debit_note_number" description="Debit note detail with customer, invoice, and item-level adjustment context for finance review." icon="document" :breadcrumbs="[['label' => 'Finance'], ['label' => 'Debit Notes', 'url' => route('finance.debit-notes.index')], ['label' => 'Details']]" :back-url="route('finance.debit-notes.index')" back-label="Back to Debit Notes" :action-url="route('finance.debit-notes.edit', $debitNote)" action-label="Edit Debit Note" />
    </x-slot>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_380px]">
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <div class="grid gap-6 md:grid-cols-2">
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Customer</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $debitNote->customer?->name ?: 'Unknown customer' }}</p></div>
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Invoice</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $debitNote->invoice?->invoice_number ?: 'No linked invoice' }}</p></div>
            </div>
            <div class="mt-8 border-t border-slate-200 pt-6"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Reason</p><p class="mt-3 text-sm leading-7 text-slate-700">{{ $debitNote->reason ?: 'No reason has been documented yet.' }}</p></div>
        </section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <h2 class="text-lg font-bold text-slate-950">Totals</h2>
            <dl class="mt-6 space-y-4 text-sm text-slate-700">
                <div class="flex items-center justify-between"><dt>Subtotal</dt><dd class="font-semibold text-slate-950">₹{{ number_format((float) $debitNote->subtotal, 2) }}</dd></div>
                <div class="flex items-center justify-between"><dt>Tax</dt><dd class="font-semibold text-slate-950">₹{{ number_format((float) $debitNote->tax_amount, 2) }}</dd></div>
                <div class="flex items-center justify-between border-t border-slate-200 pt-4"><dt>Total</dt><dd class="text-lg font-bold text-slate-950">₹{{ number_format((float) $debitNote->total, 2) }}</dd></div>
            </dl>
        </section>
    </div>
</x-app-layout>
