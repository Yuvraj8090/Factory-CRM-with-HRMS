<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" title="Attendance" description="Track shift presence, punctuality, and working-hour performance across the workforce." :action-url="route('hrms.attendances.create')" action-label="Add Attendance" />
    </x-slot>

    <section class="app-card">
        <form id="attendances-filters" method="GET" data-local-storage-form="attendances.filters" data-local-storage-clear-on-submit="false" class="grid gap-4 border-b border-slate-200 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_240px_220px_auto]">
            <div><x-input-label for="search" value="Search" /><x-text-input id="search" name="search" type="text" class="mt-2 block w-full rounded-2xl border-slate-200" :value="request('search')" placeholder="Employee name or email..." /></div>
            <div><x-input-label for="month" value="Month" /><x-text-input id="month" name="month" type="month" class="mt-2 block w-full rounded-2xl border-slate-200" :value="request('month')" /></div>
            <div><x-input-label for="status" value="Status" /><select id="status" name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm shadow-sm"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select></div>
            <div class="flex items-end"><button type="submit" class="btn btn-primary btn-block">Apply</button></div>
        </form>

        <div class="overflow-x-auto">
            <table id="attendances-table" class="table table-hover app-data-table text-sm" data-datatable-url="{{ route('hrms.attendances.index') }}" data-datatable-filter-form="#attendances-filters" data-datatable-storage-key="attendances" data-datatable-columns='@json([["data"=>"employee_name","name"=>"employeeUser.name","searchable"=>false],["data"=>"date_display","name"=>"date"],["data"=>"shift_display","name"=>"check_in","searchable"=>false],["data"=>"hours_display","name"=>"work_hours"],["data"=>"status_badge","name"=>"status","orderable"=>false,"searchable"=>false],["data"=>"actions","name"=>"id","orderable"=>false,"searchable"=>false]])'>
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    <tr><th class="px-6 py-4">Employee</th><th class="px-6 py-4">Date</th><th class="px-6 py-4">Shift</th><th class="px-6 py-4">Hours</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attendances as $attendance)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><a href="{{ route('hrms.attendances.show', $attendance) }}" class="font-bold text-slate-950 hover:text-amber-700">{{ $attendance->employeeUser?->name ?? 'Unknown user' }}</a></td>
                            <td class="px-6 py-5 text-slate-600">{{ optional($attendance->date)->format('d M Y') }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $attendance->check_in ?: '--:--' }} to {{ $attendance->check_out ?: '--:--' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ number_format((float) $attendance->work_hours, 2) }} hrs</td>
                            <td class="px-6 py-5"><x-crud.status-badge :value="$attendance->status" /></td>
                            <td class="px-6 py-5"><div class="flex justify-end gap-2"><a href="{{ route('hrms.attendances.show', $attendance) }}" class="btn btn-outline-secondary btn-sm">View</a><a href="{{ route('hrms.attendances.edit', $attendance) }}" class="btn btn-outline-secondary btn-sm">Edit</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No attendance entries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-6 py-4" data-pagination-wrapper>{{ $attendances->withQueryString()->links() }}</div>
    </section>
</x-app-layout>
