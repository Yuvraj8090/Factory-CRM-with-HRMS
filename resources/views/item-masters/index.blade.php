<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Item Masters" description="Maintain the product catalog with codes, tax data, pricing, and stock baselines that downstream finance and sales flows can trust." icon="cube" :breadcrumbs="[['label' => 'Settings'], ['label' => 'Item Masters']]" :action-url="route('settings.item-masters.create')" action-label="Add Item" />
    </x-slot>
    <section class="app-card">
        <form id="item-masters-filters" method="GET" data-local-storage-form="item-masters.filters" data-local-storage-clear-on-submit="false" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[220px_220px_auto]">
            <div><x-input-label for="category_id" value="Category" /><select id="category_id" name="category_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All categories</option>@foreach (($categories ?? collect()) as $category)<option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div><x-input-label for="active_only" value="Status" /><select id="active_only" name="active_only" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All items</option><option value="1" @selected(request('active_only') === '1')>Active only</option></select></div>
            <div class="flex items-end"><button type="submit" class="btn btn-primary btn-block">Apply Filters</button></div>
        </form>
        <div class="overflow-x-auto">
            <table id="item-masters-table" class="table table-hover app-data-table text-sm" data-datatable-url="{{ route('settings.item-masters.index') }}" data-datatable-filter-form="#item-masters-filters" data-datatable-storage-key="item-masters" data-datatable-columns='@json([["data"=>"item_name","name"=>"item_name"],["data"=>"category_name","name"=>"category.name","searchable"=>false],["data"=>"gst_rate_display","name"=>"gst_rate"],["data"=>"sale_price_display","name"=>"sale_price"],["data"=>"status_badge","name"=>"is_active","orderable"=>false,"searchable"=>false],["data"=>"actions","name"=>"id","orderable"=>false,"searchable"=>false]])'>
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Item</th><th class="px-6 py-4">Category</th><th class="px-6 py-4">GST</th><th class="px-6 py-4">Sale Price</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($items as $item)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('settings.item-masters.show', $item) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $item->item_name }}</a><p class="mt-1 text-sm text-slate-500">{{ $item->item_code }}{{ $item->unit ? ' • '.$item->unit : '' }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ $item->category?->name ?: 'Uncategorized' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ number_format((float) $item->gst_rate, 2) }}%</td>
                            <td class="px-6 py-5 text-slate-600">₹{{ number_format((float) $item->sale_price, 2) }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$item->is_active ? 'Active' : 'Inactive'" /></td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('settings.item-masters.show', $item) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('settings.item-masters.edit', $item) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No item master records have been created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>{{ $items->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
