<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$category->name" description="Category detail page for reviewing hierarchy, product usage, and availability in the master catalog." icon="folder" :breadcrumbs="[['label' => 'Settings'], ['label' => 'Categories', 'url' => route('settings.categories.index')], ['label' => 'Details']]" :back-url="route('settings.categories.index')" back-label="Back to Categories" :action-url="route('settings.categories.edit', $category)" action-label="Edit Category" />
    </x-slot>
    <div class="grid gap-6 md:grid-cols-3">
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Parent Category</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $category->parent?->name ?: 'Top-level category' }}</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$category->is_active ? 'Active' : 'Inactive'" /></div></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Item Count</p><p class="mt-2 text-2xl font-bold text-slate-950">{{ $category->items?->count() ?? 0 }}</p></section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60 md:col-span-3"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Description</p><p class="mt-3 text-sm leading-7 text-slate-700">{{ $category->description ?: 'No descriptive notes have been recorded for this category yet.' }}</p></section>
    </div>
</x-app-layout>
