<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\SalaryComponent;
use Illuminate\Support\Collection;

class PayrollCalculator
{
    public function generate(PayrollPeriod $payrollPeriod, Collection $employees): PayrollPeriod
    {
        $defaultComponents = SalaryComponent::active()->orderBy('type')->orderBy('name')->get();

        $payrollPeriod->items()->delete();

        $totals = [
            'total_gross' => 0,
            'total_deductions' => 0,
            'total_taxes' => 0,
            'total_net' => 0,
        ];

        $employees->each(function (Employee $employee) use ($payrollPeriod, $defaultComponents, &$totals): void {
            $basicSalary = (float) ($employee->salary ?? 0);

            $employeeComponents = $employee->salaryComponents()
                ->with('salaryComponent')
                ->active()
                ->get();

            $components = $employeeComponents->isNotEmpty()
                ? $employeeComponents->map(function ($component) {
                    return [
                        'name' => $component->salaryComponent?->name,
                        'type' => $component->salaryComponent?->type,
                        'value_type' => $component->value_type,
                        'amount' => (float) $component->amount,
                    ];
                })
                : $defaultComponents->map(fn (SalaryComponent $component) => [
                    'name' => $component->name,
                    'type' => $component->type,
                    'value_type' => $component->calculation_type,
                    'amount' => (float) $component->default_amount,
                ]);

            $allowances = [];
            $deductions = [];
            $taxes = [];

            $grossBeforeTaxes = $basicSalary;

            foreach ($components as $component) {
                $amount = $component['value_type'] === 'percentage'
                    ? round($basicSalary * ($component['amount'] / 100), 2)
                    : round($component['amount'], 2);

                if ($component['type'] === 'allowance') {
                    $allowances[] = ['name' => $component['name'], 'amount' => $amount];
                    $grossBeforeTaxes += $amount;
                }
            }

            foreach ($components as $component) {
                $amount = $component['value_type'] === 'percentage'
                    ? round($grossBeforeTaxes * ($component['amount'] / 100), 2)
                    : round($component['amount'], 2);

                if ($component['type'] === 'deduction') {
                    $deductions[] = ['name' => $component['name'], 'amount' => $amount];
                }

                if ($component['type'] === 'tax') {
                    $taxes[] = ['name' => $component['name'], 'amount' => $amount];
                }
            }

            $totalAllowances = collect($allowances)->sum('amount');
            $totalDeductions = collect($deductions)->sum('amount');
            $totalTaxes = collect($taxes)->sum('amount');
            $grossSalary = round($basicSalary + $totalAllowances, 2);
            $netSalary = round($grossSalary - $totalDeductions - $totalTaxes, 2);

            $payrollPeriod->items()->create([
                'employee_id' => $employee->id,
                'basic_salary' => round($basicSalary, 2),
                'gross_salary' => $grossSalary,
                'total_allowances' => round($totalAllowances, 2),
                'total_deductions' => round($totalDeductions, 2),
                'total_taxes' => round($totalTaxes, 2),
                'net_salary' => $netSalary,
                'bank_name' => $employee->bank_name,
                'bank_account_number' => $employee->bank_account_number,
                'ifsc_code' => $employee->ifsc_code,
                'status' => $payrollPeriod->status,
                'breakdown' => [
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'taxes' => $taxes,
                ],
                'generated_at' => now(),
            ]);

            $totals['total_gross'] += $grossSalary;
            $totals['total_deductions'] += $totalDeductions;
            $totals['total_taxes'] += $totalTaxes;
            $totals['total_net'] += $netSalary;
        });

        $payrollPeriod->update([
            'total_gross' => round($totals['total_gross'], 2),
            'total_deductions' => round($totals['total_deductions'], 2),
            'total_taxes' => round($totals['total_taxes'], 2),
            'total_net' => round($totals['total_net'], 2),
        ]);

        return $payrollPeriod->fresh(['items.employee.user', 'creator', 'approver']);
    }
}
