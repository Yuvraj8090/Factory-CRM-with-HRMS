<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeSalaryComponent;
use App\Models\SalaryComponent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EmployeeSalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = SalaryComponent::all()->keyBy('code');
        $effectiveFrom = Carbon::now()->startOfYear()->toDateString();

        foreach (Employee::all() as $employee) {
            $definitions = [
                ['code' => 'HRA', 'value_type' => 'fixed', 'amount' => fake()->randomFloat(2, 3500, 9000)],
                ['code' => 'CONV', 'value_type' => 'fixed', 'amount' => fake()->randomFloat(2, 1200, 3200)],
                ['code' => 'SPAL', 'value_type' => 'fixed', 'amount' => fake()->randomFloat(2, 1500, 6500)],
                ['code' => 'PF', 'value_type' => 'percentage', 'amount' => 12],
                ['code' => 'PT', 'value_type' => 'fixed', 'amount' => 200],
                ['code' => 'TDS', 'value_type' => 'percentage', 'amount' => fake()->randomFloat(2, 3, 8)],
            ];

            foreach ($definitions as $definition) {
                EmployeeSalaryComponent::create([
                    'employee_id' => $employee->id,
                    'salary_component_id' => $components[$definition['code']]->id,
                    'value_type' => $definition['value_type'],
                    'amount' => $definition['amount'],
                    'effective_from' => $effectiveFrom,
                    'effective_to' => null,
                    'is_active' => true,
                    'notes' => fake()->sentence(),
                ]);
            }
        }
    }
}
