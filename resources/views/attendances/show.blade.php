<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" :title="($attendance->employeeUser?->name ?? 'Attendance Record')" description="Attendance detail with computed work and overtime hours for the selected shift." :back-url="route('hrms.attendances.index')" back-label="Back to Attendance">
            <a href="{{ route('hrms.attendances.edit', $attendance) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Edit Attendance</a>
        </x-crud.page-header>
    </x-slot>

    <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
        <div class="grid gap-6 lg:grid-cols-2">
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Employee</p><p class="mt-2 text-lg font-semibold text-slate-950">{{ $attendance->employeeUser?->name ?? 'Unknown user' }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$attendance->status" /></div></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Date</p><p class="mt-2 text-sm text-slate-700">{{ optional($attendance->date)->format('d M Y') }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Shift Window</p><p class="mt-2 text-sm text-slate-700">{{ $attendance->check_in ?: '--:--' }} to {{ $attendance->check_out ?: '--:--' }}</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Work Hours</p><p class="mt-2 text-sm text-slate-700">{{ number_format((float) $attendance->work_hours, 2) }} hrs</p></div>
            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Overtime</p><p class="mt-2 text-sm text-slate-700">{{ number_format((float) $attendance->overtime_hours, 2) }} hrs</p></div>
            <div class="lg:col-span-2"><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Notes</p><p class="mt-2 text-sm text-slate-700">{{ $attendance->notes ?: 'No notes recorded.' }}</p></div>
        </div>
    </section>
</x-app-layout>
