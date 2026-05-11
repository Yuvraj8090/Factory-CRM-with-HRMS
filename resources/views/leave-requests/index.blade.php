<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Leave Requests" description="Track leave demand, approvals, and schedule impact with one consolidated register." :action-url="route('hrms.leave-requests.create')" action-label="Add Leave Request" />
    </x-slot>

    <section class="rounded-3xl border border-white/70 bg-white shadow-sm shadow-slate-200/60">
        <form method="GET" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_220px_auto]">
            <div><x-input-label for="search" value="Search" /><x-text-input id="search" name="search" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="request('search')" placeholder="Employee name or email..." /></div>
            <div><x-input-label for="status" value="Status" /><select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select></div>
            <div class="flex items-end"><button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Apply</button></div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-6 py-4">Employee</th><th class="px-6 py-4">Leave Type</th><th class="px-6 py-4">Dates</th><th class="px-6 py-4">Days</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leaveRequests as $leaveRequest)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('hrms.leave-requests.show', $leaveRequest) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $leaveRequest->user?->name ?? 'Unknown user' }}</a></td>
                            <td class="px-6 py-5 text-slate-600">{{ $leaveRequest->leaveType?->name ?: 'Not set' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ optional($leaveRequest->start_date)->format('d M Y') }} to {{ optional($leaveRequest->end_date)->format('d M Y') }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ number_format((float) $leaveRequest->total_days, 2) }}</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$leaveRequest->status" /></td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('hrms.leave-requests.show', $leaveRequest) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View</a><a href="{{ route('hrms.leave-requests.edit', $leaveRequest) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No leave requests matched the current filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4">{{ $leaveRequests->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
