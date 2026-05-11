<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$department->name" description="Department detail page for reviewing workforce coverage, designation structure, and operating notes." icon="building" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Departments', 'url' => route('hrms.departments.index')], ['label' => 'Details']]" :back-url="route('hrms.departments.index')" back-label="Back to Departments" :action-url="route('hrms.departments.edit', $department)" action-label="Edit Department" />
    </x-slot>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_380px]">
        <section class="app-card app-card-body">
            <div class="grid gap-6 md:grid-cols-2">
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$department->is_active ? 'Active' : 'Inactive'" /></div></div>
                <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Employees</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $department->employees?->count() ?? 0 }}</p></div>
            </div>
            <div class="mt-8 border-t border-slate-200 pt-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Description</p>
                <p class="mt-3 text-sm leading-7 text-slate-700">{{ $department->description ?: 'No department description has been recorded yet.' }}</p>
            </div>
        </section>
        <section class="app-card app-card-body">
            <h2 class="text-lg font-bold text-slate-950">Attached Designations</h2>
            <div class="mt-6 space-y-3">
                @forelse ($department->designations ?? [] as $designation)
                    <div class="app-panel px-4 py-3">
                        <p class="font-semibold text-slate-950">{{ $designation->name }}</p>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">No designations are mapped to this department yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
