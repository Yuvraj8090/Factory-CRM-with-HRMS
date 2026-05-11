<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" :title="$employee->user?->name ?? 'Employee'" description="Full employee record with employment, compliance, and compensation context." :back-url="route('hrms.employees.index')" back-label="Back to Employees">
            <a href="{{ route('hrms.employees.edit', $employee) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Edit Employee</a>
        </x-crud.page-header>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.9fr)]">
        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <div class="grid gap-6 lg:grid-cols-2">
                <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Employee Code</p><p class="mt-2 text-lg font-semibold text-slate-950">{{ $employee->employee_code }}</p></div>
                <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Status</p><div class="mt-2"><x-crud.status-badge :value="$employee->is_active ? 'Active' : 'Inactive'" /></div></div>
                <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Department</p><p class="mt-2 text-sm text-slate-700">{{ $employee->department?->name ?: 'Unassigned' }}</p></div>
                <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Designation</p><p class="mt-2 text-sm text-slate-700">{{ $employee->designation?->name ?: 'Unassigned' }}</p></div>
                <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Date of Joining</p><p class="mt-2 text-sm text-slate-700">{{ optional($employee->date_of_joining)->format('d M Y') }}</p></div>
                <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Date of Birth</p><p class="mt-2 text-sm text-slate-700">{{ optional($employee->date_of_birth)->format('d M Y') ?: 'Not available' }}</p></div>
                <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Emergency Contact</p><p class="mt-2 text-sm text-slate-700">{{ $employee->emergency_contact_name ?: 'Not available' }}{{ $employee->emergency_contact_phone ? ' • '.$employee->emergency_contact_phone : '' }}</p></div>
                <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Banking</p><p class="mt-2 text-sm text-slate-700">{{ $employee->bank_name ?: 'Not available' }}{{ $employee->bank_account_number ? ' • '.$employee->bank_account_number : '' }}</p></div>
            </div>
        </section>

        <section class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm shadow-slate-200/60">
            <h2 class="text-lg font-bold text-slate-950">Compensation Snapshot</h2>
            <div class="mt-5 grid gap-4">
                <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Monthly Salary</p><p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $employee->salary, 2) }}</p></div>
                <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">PF Number</p><p class="mt-2 text-sm font-semibold text-slate-950">{{ $employee->pf_number ?: 'Not available' }}</p></div>
                <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">ESIC Number</p><p class="mt-2 text-sm font-semibold text-slate-950">{{ $employee->esic_number ?: 'Not available' }}</p></div>
            </div>
        </section>
    </div>
</x-app-layout>
