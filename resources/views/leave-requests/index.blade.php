<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Leave Requests" description="Track leave demand, approvals, and schedule impact with one consolidated register." :action-url="route('hrms.leave-requests.create')" action-label="Add Leave Request" />
    </x-slot>

    <section class="app-card">
        <form id="leave-requests-filters" method="GET" data-local-storage-form="leave-requests.filters" data-local-storage-clear-on-submit="false" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_220px_auto]">
            <div><x-input-label for="search" value="Search" /><x-text-input id="search" name="search" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="request('search')" placeholder="Employee name or email..." /></div>
            <div><x-input-label for="status" value="Status" /><select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select></div>
            <div class="flex items-end"><button type="submit" class="btn btn-primary btn-block">Apply</button></div>
        </form>

        <div class="overflow-x-auto">
            <table id="leave-requests-table" class="table table-hover app-data-table text-sm" data-datatable-url="{{ route('hrms.leave-requests.index') }}" data-datatable-filter-form="#leave-requests-filters" data-datatable-storage-key="leave-requests" data-datatable-columns='@json([["data"=>"employee_name","name"=>"user.name","searchable"=>false],["data"=>"leave_type_name","name"=>"leaveType.name","searchable"=>false],["data"=>"dates_display","name"=>"start_date"],["data"=>"days_total","name"=>"total_days"],["data"=>"status_badge","name"=>"status","orderable"=>false,"searchable"=>false],["data"=>"actions","name"=>"id","orderable"=>false,"searchable"=>false]])'>
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Employee</th><th class="px-6 py-4">Leave Type</th><th class="px-6 py-4">Dates</th><th class="px-6 py-4">Days</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leaveRequests as $leaveRequest)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('hrms.leave-requests.show', $leaveRequest) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $leaveRequest->user?->name ?? 'Unknown user' }}</a></td>
                            <td class="px-6 py-5 text-slate-600">{{ $leaveRequest->leaveType?->name ?: 'Not set' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ optional($leaveRequest->start_date)->format('d M Y') }} to {{ optional($leaveRequest->end_date)->format('d M Y') }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ number_format((float) $leaveRequest->total_days, 2) }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$leaveRequest->status" /></td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('hrms.leave-requests.show', $leaveRequest) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('hrms.leave-requests.edit', $leaveRequest) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No leave requests matched the current filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>{{ $leaveRequests->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
