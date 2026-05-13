<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Designations" description="Manage the role titles that define hierarchy, responsibility, and career progression across the workforce." icon="tag" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Designations']]" :action-url="route('hrms.designations.create')" action-label="Add Designation" />
    </x-slot>
    <section class="app-card">
        <div class="overflow-x-auto">
            <table id="designations-table" class="table table-hover app-data-table text-sm" data-datatable-url="{{ route('hrms.designations.index') }}" data-datatable-storage-key="designations" data-datatable-columns='{{ \Illuminate\Support\Js::from([["data"=>"name","name"=>"name"],["data"=>"department_name","name"=>"department.name","searchable"=>false],["data"=>"employees_total","name"=>"employees_count"],["data"=>"actions","name"=>"id","orderable"=>false,"searchable"=>false]]) }}'>
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Designation</th><th class="px-6 py-4">Department</th><th class="px-6 py-4">Employees</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($designations as $designation)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('hrms.designations.show', $designation) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $designation->name }}</a></td>
                            <td class="px-6 py-5 text-slate-600">{{ $designation->department?->name ?: 'Unassigned' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $designation->employees_count ?? $designation->employees?->count() ?? 0 }}</td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('hrms.designations.show', $designation) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('hrms.designations.edit', $designation) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-16 text-center text-sm text-slate-500">No designations are available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>{{ $designations->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
