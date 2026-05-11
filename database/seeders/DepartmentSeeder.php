<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Sales & Marketing', 'description' => 'Owns lead generation, account development, and commercial growth.'],
            ['name' => 'Human Resources', 'description' => 'Manages recruitment, attendance, leave, and people operations.'],
            ['name' => 'Finance & Accounts', 'description' => 'Controls invoicing, collections, payments, and statutory bookkeeping.'],
            ['name' => 'Production', 'description' => 'Runs day-to-day manufacturing and line operations.'],
            ['name' => 'Quality Assurance', 'description' => 'Handles compliance, audits, and quality checks for finished goods.'],
        ];

        foreach ($departments as $department) {
            Department::create([
                ...$department,
                'is_active' => true,
            ]);
        }
    }
}
