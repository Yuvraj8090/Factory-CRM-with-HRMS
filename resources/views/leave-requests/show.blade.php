<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" :title="($leaveRequest->user?->name ?? 'Leave Request')" description="Request detail with duration, approval ownership, and leave-type context." :back-url="route('hrms.leave-requests.index')" back-label="Back to Leave Requests">
            <a href="{{ route('hrms.leave-requests.edit', $leaveRequest) }}" class="btn btn-primary">Edit Request</a>
        </x-crud.page-header>
    </x-slot>

    <section class="app-card app-card-body">
        <div class="grid gap-6 lg:grid-cols-2">
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Employee</p><p class="mt-2 text-lg font-semibold text-slate-950">{{ $leaveRequest->user?->name ?? 'Unknown user' }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$leaveRequest->status" /></div></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Leave Type</p><p class="mt-2 text-sm text-slate-700">{{ $leaveRequest->leaveType?->name ?: 'Not available' }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Approved By</p><p class="mt-2 text-sm text-slate-700">{{ $leaveRequest->approver?->name ?: 'Pending approval' }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Date Range</p><p class="mt-2 text-sm text-slate-700">{{ optional($leaveRequest->start_date)->format('d M Y') }} to {{ optional($leaveRequest->end_date)->format('d M Y') }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Total Days</p><p class="mt-2 text-sm text-slate-700">{{ number_format((float) $leaveRequest->total_days, 2) }}</p></div>
            <div class="lg:col-span-2"><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Reason</p><p class="mt-2 text-sm text-slate-700">{{ $leaveRequest->reason ?: 'No reason provided.' }}</p></div>
        </div>
    </section>
</x-app-layout>
