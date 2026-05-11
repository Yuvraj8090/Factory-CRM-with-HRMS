@php($item = $item ?? $itemMaster ?? $item_master)
<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$item->item_name" description="Review the master definition of this product, including category, commercial pricing, and GST identity." icon="cube" :breadcrumbs="[['label' => 'Settings'], ['label' => 'Item Masters', 'url' => route('settings.item-masters.index')], ['label' => 'Details']]" :back-url="route('settings.item-masters.index')" back-label="Back to Item Masters" :action-url="route('settings.item-masters.edit', $item)" action-label="Edit Item" />
    </x-slot>
    <div class="grid gap-6 md:grid-cols-3">
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Item Code</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $item->item_code }}</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Category</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $item->category?->name ?: 'Uncategorized' }}</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$item->is_active ? 'Active' : 'Inactive'" /></div></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">GST Rate</p><p class="mt-2 text-base font-semibold text-slate-950">{{ number_format((float) $item->gst_rate, 2) }}%</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Sale Price</p><p class="mt-2 text-base font-semibold text-slate-950">₹{{ number_format((float) $item->sale_price, 2) }}</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Opening Stock</p><p class="mt-2 text-base font-semibold text-slate-950">{{ number_format((float) $item->opening_stock, 2) }} {{ $item->unit }}</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60 md:col-span-3"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Description</p><p class="mt-3 text-sm leading-7 text-slate-700">{{ $item->description ?: 'No descriptive notes have been added for this item yet.' }}</p></section>
    </div>
</x-app-layout>
