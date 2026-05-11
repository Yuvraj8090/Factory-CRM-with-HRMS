<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$designation->name" description="Review the designation, its department linkage, and the employee records attached to this role." icon="tag" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Designations', 'url' => route('hrms.designations.index')], ['label' => 'Details']]" :back-url="route('hrms.designations.index')" back-label="Back to Designations" :action-url="route('hrms.designations.edit', $designation)" action-label="Edit Designation" />
    </x-slot>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_380px]">
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <div><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Department</p><p class="mt-2 text-base font-semibold text-slate-950">{{ $designation->department?->name ?: 'Not assigned' }}</p></div>
        </section>
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <h2 class="text-lg font-bold text-slate-950">Employees in This Role</h2>
            <div class="mt-6 space-y-3">
                @forelse ($designation->employees ?? [] as $employee)
                    <div class="rounded-2xl border border-slate-200 px-4 py-3"><p class="font-semibold text-slate-950">{{ $employee->user?->name ?: $employee->employee_code }}</p><p class="text-sm text-slate-500">{{ $employee->employee_code }}</p></div>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500">No employees are assigned to this designation yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
