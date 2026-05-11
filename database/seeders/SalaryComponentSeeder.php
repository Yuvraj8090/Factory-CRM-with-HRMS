<?php

namespace Database\Seeders;

use App\Models\SalaryComponent;
use Illuminate\Database\Seeder;

class SalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            ['name' => 'House Rent Allowance', 'code' => 'HRA', 'type' => 'allowance', 'calculation_type' => 'fixed', 'default_amount' => 6000, 'is_taxable' => true],
            ['name' => 'Conveyance Allowance', 'code' => 'CONV', 'type' => 'allowance', 'calculation_type' => 'fixed', 'default_amount' => 2400, 'is_taxable' => true],
            ['name' => 'Special Allowance', 'code' => 'SPAL', 'type' => 'allowance', 'calculation_type' => 'fixed', 'default_amount' => 3500, 'is_taxable' => true],
            ['name' => 'Provident Fund', 'code' => 'PF', 'type' => 'deduction', 'calculation_type' => 'percentage', 'default_amount' => 12, 'is_taxable' => false],
            ['name' => 'Professional Tax', 'code' => 'PT', 'type' => 'deduction', 'calculation_type' => 'fixed', 'default_amount' => 200, 'is_taxable' => false],
            ['name' => 'Income Tax', 'code' => 'TDS', 'type' => 'tax', 'calculation_type' => 'percentage', 'default_amount' => 5, 'is_taxable' => true],
        ];

        foreach ($components as $component) {
            SalaryComponent::create([
                ...$component,
                'is_active' => true,
                'description' => fake()->sentence(10),
            ]);
        }
    }
}
