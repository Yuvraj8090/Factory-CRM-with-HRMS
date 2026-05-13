<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Leave Types" description="Manage the approved leave categories and yearly entitlement limits used by HR approvals and employee planning." icon="folder" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Leave Types']]" :action-url="route('hrms.leave-types.create')" action-label="Add Leave Type" />
    </x-slot>
    <section class="app-card">
        <div class="overflow-x-auto">
            <table id="leave-types-table" class="table table-hover app-data-table text-sm" data-datatable-url="{{ route('hrms.leave-types.index') }}" data-datatable-storage-key="leave-types" data-datatable-columns='{{ \Illuminate\Support\Js::from([["data"=>"name","name"=>"name"],["data"=>"leave_days","name"=>"leave_days_per_year"],["data"=>"paid_status","name"=>"is_paid","orderable"=>false,"searchable"=>false],["data"=>"requests_total","name"=>"leave_requests_count"],["data"=>"actions","name"=>"id","orderable"=>false,"searchable"=>false]]) }}'>
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Leave Type</th><th class="px-6 py-4">Days Per Year</th><th class="px-6 py-4">Paid Status</th><th class="px-6 py-4">Requests</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leaveTypes as $leaveType)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('hrms.leave-types.show', $leaveType) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $leaveType->name }}</a></td>
                            <td class="px-6 py-5 text-slate-600">{{ $leaveType->leave_days_per_year }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$leaveType->is_paid ? 'Paid' : 'Unpaid'" /></td>
                            <td class="px-6 py-5 text-slate-600">{{ $leaveType->leave_requests_count ?? $leaveType->leaveRequests?->count() ?? 0 }}</td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('hrms.leave-types.show', $leaveType) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('hrms.leave-types.edit', $leaveType) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">No leave types have been configured yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>{{ $leaveTypes->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
