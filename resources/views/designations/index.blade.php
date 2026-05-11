<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Designations" description="Manage the role titles that define hierarchy, responsibility, and career progression across the workforce." icon="tag" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Designations']]" :action-url="route('hrms.designations.create')" action-label="Add Designation" />
    </x-slot>
    <section class="rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Designation</th><th class="px-6 py-4">Department</th><th class="px-6 py-4">Employees</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($designations as $designation)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('hrms.designations.show', $designation) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $designation->name }}</a></td>
                            <td class="px-6 py-5 text-slate-600">{{ $designation->department?->name ?: 'Unassigned' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $designation->employees_count ?? $designation->employees?->count() ?? 0 }}</td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('hrms.designations.show', $designation) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View</a><a href="{{ route('hrms.designations.edit', $designation) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-16 text-center text-sm text-slate-500">No designations are available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $designations->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
