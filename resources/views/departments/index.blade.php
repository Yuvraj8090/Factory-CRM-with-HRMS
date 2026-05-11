<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Departments" description="Maintain the workforce structure used for employee assignment, reporting, and HR planning across the factory." icon="building" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Departments']]" :action-url="route('hrms.departments.create')" action-label="Add Department" />
    </x-slot>
    <section class="app-card">
        <div class="overflow-x-auto">
            <table class="table table-hover app-data-table text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    <tr><th class="px-6 py-4">Department</th><th class="px-6 py-4">Designations</th><th class="px-6 py-4">Employees</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($departments as $department)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('hrms.departments.show', $department) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $department->name }}</a><p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($department->description, 90) ?: 'No department description added yet.' }}</p></td>
                            <td class="px-6 py-5 text-slate-600">{{ $department->designations_count ?? $department->designations?->count() ?? 0 }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $department->employees_count ?? $department->employees?->count() ?? 0 }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$department->is_active ? 'Active' : 'Inactive'" /></td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('hrms.departments.show', $department) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('hrms.departments.edit', $department) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">No departments are available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $departments->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
