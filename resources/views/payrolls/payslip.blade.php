<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header eyebrow="HRMS Workspace" :title="'Payslip • '.($payrollItem->employee?->user?->name ?? 'Employee')" description="Printable payslip view for the selected payroll cycle." :back-url="route('hrms.payrolls.show', $payroll)" back-label="Back to Payroll" />
    </x-slot>

    <section class="mx-auto max-w-4xl rounded-3xl border border-white/70 bg-white p-8 shadow-sm shadow-slate-200/60">
        <div class="flex items-start justify-between gap-6 border-b border-slate-200 pb-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Food Processing Factory</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-950">Payslip</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $payroll->name }}</p>
            </div>
            <div class="text-right text-sm text-slate-600">
                <p>{{ $payrollItem->employee?->user?->name }}</p>
                <p>{{ $payrollItem->employee?->employee_code }}</p>
                <p>{{ optional($payroll->period_start)->format('d M Y') }} to {{ optional($payroll->period_end)->format('d M Y') }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-5">
                <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Earnings</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-700">
                    <div class="flex justify-between"><span>Basic Salary</span><span>₹{{ number_format((float) $payrollItem->basic_salary, 2) }}</span></div>
                    @foreach (($payrollItem->breakdown['allowances'] ?? []) as $allowance)
                        <div class="flex justify-between"><span>{{ $allowance['name'] }}</span><span>₹{{ number_format((float) $allowance['amount'], 2) }}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5">
                <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Deductions & Tax</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-700">
                    @foreach (($payrollItem->breakdown['deductions'] ?? []) as $deduction)
                        <div class="flex justify-between"><span>{{ $deduction['name'] }}</span><span>₹{{ number_format((float) $deduction['amount'], 2) }}</span></div>
                    @endforeach
                    @foreach (($payrollItem->breakdown['taxes'] ?? []) as $tax)
                        <div class="flex justify-between"><span>{{ $tax['name'] }}</span><span>₹{{ number_format((float) $tax['amount'], 2) }}</span></div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-4">
            <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Gross</p><p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $payrollItem->gross_salary, 2) }}</p></div>
            <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Deductions</p><p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $payrollItem->total_deductions, 2) }}</p></div>
            <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-500">Taxes</p><p class="mt-2 text-lg font-bold text-slate-950">₹{{ number_format((float) $payrollItem->total_taxes, 2) }}</p></div>
            <div class="rounded-2xl bg-emerald-50 px-4 py-4"><p class="text-xs uppercase tracking-[0.16em] text-emerald-700">Net Salary</p><p class="mt-2 text-lg font-bold text-emerald-900">₹{{ number_format((float) $payrollItem->net_salary, 2) }}</p></div>
        </div>
    </section>
</x-app-layout>
