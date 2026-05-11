<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'Sales & Marketing' => ['Regional Sales Manager', 'Key Account Executive'],
            'Human Resources' => ['HR Manager', 'HR Executive'],
            'Finance & Accounts' => ['Accounts Manager', 'Accounts Executive'],
            'Production' => ['Production Supervisor', 'Machine Operator'],
            'Quality Assurance' => ['Quality Lead', 'QA Inspector'],
        ];

        foreach ($map as $departmentName => $designations) {
            $department = Department::where('name', $departmentName)->firstOrFail();

            foreach ($designations as $designationName) {
                Designation::create([
                    'name' => $designationName,
                    'department_id' => $department->id,
                ]);
            }
        }
    }
}
