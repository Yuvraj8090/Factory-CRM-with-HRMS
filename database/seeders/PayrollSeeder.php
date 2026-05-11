<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $creatorId = User::role(['HR Manager', 'HR'])->inRandomOrder()->value('id');
        $approverId = User::role(['Manager', 'Admin'])->inRandomOrder()->value('id');
        $employees = Employee::with(['salaryComponents.salaryComponent'])->get();
        $statuses = ['draft', 'review', 'approved', 'paid', 'paid'];

        for ($monthOffset = 4; $monthOffset >= 0; $monthOffset--) {
            $periodStart = Carbon::now()->subMonths($monthOffset)->startOfMonth();
            $periodEnd = (clone $periodStart)->endOfMonth();
            $status = $statuses[4 - $monthOffset];

            $period = PayrollPeriod::create([
                'name' => $periodStart->format('F Y Payroll'),
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'payout_date' => (clone $periodEnd)->addDays(5)->toDateString(),
                'status' => $status,
                'total_gross' => 0,
                'total_deductions' => 0,
                'total_taxes' => 0,
                'total_net' => 0,
                'notes' => 'Demo payroll run generated for seeded environment.',
                'created_by' => $creatorId,
                'approved_by' => in_array($status, ['approved', 'paid'], true) ? $approverId : null,
                'approved_at' => in_array($status, ['approved', 'paid'], true) ? now()->subDays($monthOffset * 10) : null,
            ]);

            $grossTotal = 0;
            $deductionTotal = 0;
            $taxTotal = 0;
            $netTotal = 0;

            foreach ($employees as $employee) {
                $allowances = [];
                $deductions = [];
                $taxes = [];
                $basic = (float) $employee->salary;
                $totalAllowances = 0;
                $totalDeductions = 0;
                $totalTaxes = 0;

                foreach ($employee->salaryComponents as $component) {
                    $master = $component->salaryComponent;
                    $amount = $component->value_type === 'percentage'
                        ? round($basic * ((float) $component->amount / 100), 2)
                        : (float) $component->amount;

                    $entry = ['name' => $master->name, 'amount' => $amount];

                    if ($master->type === 'allowance') {
                        $allowances[] = $entry;
                        $totalAllowances += $amount;
                    } elseif ($master->type === 'deduction') {
                        $deductions[] = $entry;
                        $totalDeductions += $amount;
                    } else {
                        $taxes[] = $entry;
                        $totalTaxes += $amount;
                    }
                }

                $gross = round($basic + $totalAllowances, 2);
                $net = round($gross - $totalDeductions - $totalTaxes, 2);

                $period->items()->create([
                    'employee_id' => $employee->id,
                    'basic_salary' => $basic,
                    'gross_salary' => $gross,
                    'total_allowances' => $totalAllowances,
                    'total_deductions' => $totalDeductions,
                    'total_taxes' => $totalTaxes,
                    'net_salary' => $net,
                    'bank_name' => $employee->bank_name,
                    'bank_account_number' => $employee->bank_account_number,
                    'ifsc_code' => $employee->ifsc_code,
                    'status' => $status,
                    'breakdown' => [
                        'allowances' => $allowances,
                        'deductions' => $deductions,
                        'taxes' => $taxes,
                    ],
                    'remarks' => fake()->optional()->sentence(),
                    'generated_at' => now()->subDays($monthOffset * 5),
                ]);

                $grossTotal += $gross;
                $deductionTotal += $totalDeductions;
                $taxTotal += $totalTaxes;
                $netTotal += $net;
            }

            $period->update([
                'total_gross' => round($grossTotal, 2),
                'total_deductions' => round($deductionTotal, 2),
                'total_taxes' => round($taxTotal, 2),
                'total_net' => round($netTotal, 2),
            ]);
        }
    }
}
