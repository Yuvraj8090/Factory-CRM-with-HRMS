<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" :title="$payroll->name" description="Payroll run detail with approval workflow, totals, and employee-wise settlement lines." :back-url="route('hrms.payrolls.index')" back-label="Back to Payroll">
            <div class="flex flex-wrap gap-3">
                @if ($payroll->status === 'draft')
                    <form action="{{ route('hrms.payrolls.submit-review', $payroll) }}" method="POST">@csrf <button type="submit" class="btn btn-outline-secondary">Submit for Review</button></form>
                @endif
                @if (in_array($payroll->status, ['draft', 'review'], true))
                    <form action="{{ route('hrms.payrolls.approve', $payroll) }}" method="POST">@csrf <button type="submit" class="btn btn-success">Approve Payroll</button></form>
                @endif
                <a href="{{ route('hrms.payrolls.bank-transfer', $payroll) }}" class="btn btn-primary">Export Bank File</a>
            </div>
        </x-crud.page-header>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <div class="app-stat-card"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Gross</p><p class="mt-2 text-2xl font-bold text-slate-950">₹{{ number_format((float) $payroll->total_gross, 2) }}</p></div>
            <div class="app-stat-card"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Deductions</p><p class="mt-2 text-2xl font-bold text-slate-950">₹{{ number_format((float) $payroll->total_deductions, 2) }}</p></div>
            <div class="app-stat-card"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Taxes</p><p class="mt-2 text-2xl font-bold text-slate-950">₹{{ number_format((float) $payroll->total_taxes, 2) }}</p></div>
            <div class="app-stat-card"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Net Pay</p><p class="mt-2 text-2xl font-bold text-slate-950">₹{{ number_format((float) $payroll->total_net, 2) }}</p></div>
        </div>

        <section class="app-card app-card-body">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Employee Breakdown</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $payroll->items->count() }} salary slips prepared for this cycle.</p>
                </div>
                <x-crud.status-badge :value="ucfirst($payroll->status)" />
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="table table-hover app-data-table text-sm">
                    <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"><tr><th class="px-4 py-3">Employee</th><th class="px-4 py-3">Basic</th><th class="px-4 py-3">Allowances</th><th class="px-4 py-3">Deductions</th><th class="px-4 py-3">Taxes</th><th class="px-4 py-3">Net Salary</th><th class="px-4 py-3 text-right">Payslip</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($payroll->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $item->employee?->user?->name ?? 'Unknown employee' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->employee?->employee_code }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">₹{{ number_format((float) $item->basic_salary, 2) }}</td>
                                <td class="px-4 py-3 text-slate-600">₹{{ number_format((float) $item->total_allowances, 2) }}</td>
                                <td class="px-4 py-3 text-slate-600">₹{{ number_format((float) $item->total_deductions, 2) }}</td>
                                <td class="px-4 py-3 text-slate-600">₹{{ number_format((float) $item->total_taxes, 2) }}</td>
                                <td class="px-4 py-3 text-slate-600">₹{{ number_format((float) $item->net_salary, 2) }}</td>
                                <td class="px-4 py-3 text-right"><a href="{{ route('hrms.payrolls.payslip', [$payroll, $item]) }}" class="btn btn-outline-secondary btn-sm">View Payslip</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
