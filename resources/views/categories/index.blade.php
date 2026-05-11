<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Categories" description="Shape the master product taxonomy used by inventory, invoicing, and product reporting across the business." icon="folder" :breadcrumbs="[['label' => 'Settings'], ['label' => 'Categories']]" :action-url="route('settings.categories.create')" action-label="Add Category" />
    </x-slot>
    <section class="rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Category</th><th class="px-6 py-4">Parent</th><th class="px-6 py-4">Items</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('settings.categories.show', $category) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $category->name }}</a><p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($category->description, 80) ?: 'No category description added yet.' }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ $category->parent?->name ?: 'Top-level' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $category->items_count ?? $category->items?->count() ?? 0 }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$category->is_active ? 'Active' : 'Inactive'" /></td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('settings.categories.show', $category) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View</a><a href="{{ route('settings.categories.edit', $category) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">No categories have been created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $categories->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
