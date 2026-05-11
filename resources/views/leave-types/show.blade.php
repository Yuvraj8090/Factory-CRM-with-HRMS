@php($leaveType = $leaveType ?? $leave_type)
<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header :title="$leaveType->name" description="Review the leave entitlement, pay treatment, and usage footprint for this leave category." icon="folder" :breadcrumbs="[['label' => 'HRMS'], ['label' => 'Leave Types', 'url' => route('hrms.leave-types.index')], ['label' => 'Details']]" :back-url="route('hrms.leave-types.index')" back-label="Back to Leave Types" :action-url="route('hrms.leave-types.edit', $leaveType)" action-label="Edit Leave Type" />
    </x-slot>
    <div class="grid gap-6 md:grid-cols-3">
        <section class="app-card app-card-body">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Days Per Year</p>
            <p class="mt-2 text-2xl font-bold text-slate-950">{{ $leaveType->leave_days_per_year }}</p>
        </section>
        <section class="app-card app-card-body">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Compensation Type</p>
            <div class="mt-2"><x-crud.status-badge :value="$leaveType->is_paid ? 'Paid' : 'Unpaid'" /></div>
        </section>
        <section class="app-card app-card-body">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Requests Logged</p>
            <p class="mt-2 text-2xl font-bold text-slate-950">{{ $leaveType->leaveRequests?->count() ?? 0 }}</p>
        </section>
    </div>
</x-app-layout>
