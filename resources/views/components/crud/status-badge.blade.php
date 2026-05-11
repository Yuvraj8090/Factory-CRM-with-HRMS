@props([
    'value' => 'Draft',
    'map' => [],
])

@php
    $defaultClasses = [
        'Active' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'Inactive' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'Blocked' => 'bg-rose-100 text-rose-800 ring-rose-200',
        'Pending' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'Approved' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'Rejected' => 'bg-rose-100 text-rose-800 ring-rose-200',
        'Present' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'Absent' => 'bg-rose-100 text-rose-800 ring-rose-200',
        'Late' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'Half Day' => 'bg-orange-100 text-orange-800 ring-orange-200',
        'Leave' => 'bg-sky-100 text-sky-800 ring-sky-200',
        'Draft' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'Sent' => 'bg-sky-100 text-sky-800 ring-sky-200',
        'Accepted' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'Paid' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'Partial' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'Overdue' => 'bg-rose-100 text-rose-800 ring-rose-200',
        'Cancelled' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'Completed' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'In Progress' => 'bg-indigo-100 text-indigo-800 ring-indigo-200',
        'Open' => 'bg-sky-100 text-sky-800 ring-sky-200',
    ];

    $classes = $map[$value] ?? $defaultClasses[$value] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
@endphp

<span {{ $attributes->merge(['class' => $classes . ' inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset']) }}>
    {{ $value }}
</span>
