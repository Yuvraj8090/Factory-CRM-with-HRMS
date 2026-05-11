<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $userEmails = [
            'user@example.com',
            'manager@factorycrm.test',
            'hr-manager@factorycrm.test',
            'hr-executive@factorycrm.test',
            'saleslead.north@factorycrm.test',
            'saleslead.west@factorycrm.test',
            'saleslead.south@factorycrm.test',
            'salesrep.a@factorycrm.test',
            'salesrep.b@factorycrm.test',
            'salesrep.c@factorycrm.test',
            'supervisor@factorycrm.test',
            'operator@factorycrm.test',
        ];

        $users = User::whereIn('email', $userEmails)->get()->keyBy('email');

        foreach ($userEmails as $index => $email) {
            $user = $users[$email];
            $department = $this->resolveDepartmentForUser($user);
            $designation = $this->resolveDesignationForDepartment($department, $user);

            Employee::create([
                'user_id' => $user->id,
                'employee_code' => 'EMP-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'department_id' => $department->id,
                'designation_id' => $designation->id,
                'date_of_joining' => fake()->dateTimeBetween('-5 years', '-6 months'),
                'date_of_birth' => fake()->dateTimeBetween('-45 years', '-22 years'),
                'gender' => fake()->randomElement(['Male', 'Female']),
                'marital_status' => fake()->randomElement(['Single', 'Married']),
                'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+']),
                'emergency_contact_name' => fake()->name(),
                'emergency_contact_phone' => '88' . str_pad((string) (40000000 + $index), 8, '0', STR_PAD_LEFT),
                'bank_name' => fake()->randomElement(['HDFC Bank', 'ICICI Bank', 'Axis Bank', 'SBI']),
                'bank_account_number' => '50100' . str_pad((string) ($index + 1), 8, '0', STR_PAD_LEFT),
                'ifsc_code' => fake()->randomElement(['HDFC0001234', 'ICIC0002345', 'UTIB0003456', 'SBIN0004567']),
                'pf_number' => 'PF' . str_pad((string) ($index + 1), 8, '0', STR_PAD_LEFT),
                'esic_number' => 'ESIC' . str_pad((string) ($index + 1), 7, '0', STR_PAD_LEFT),
                'salary' => fake()->randomFloat(2, 22000, 95000),
                'is_active' => true,
            ]);
        }
    }

    private function resolveDepartmentForUser(User $user): Department
    {
        if ($user->hasAnyRole(['Sales Lead', 'Sales Rep'])) {
            return Department::where('name', 'Sales & Marketing')->firstOrFail();
        }

        if ($user->hasAnyRole(['HR Manager', 'HR'])) {
            return Department::where('name', 'Human Resources')->firstOrFail();
        }

        if ($user->hasRole('Manager')) {
            return Department::where('name', 'Production')->firstOrFail();
        }

        return fake()->randomElement([
            Department::where('name', 'Production')->firstOrFail(),
            Department::where('name', 'Quality Assurance')->firstOrFail(),
            Department::where('name', 'Finance & Accounts')->firstOrFail(),
        ]);
    }

    private function resolveDesignationForDepartment(Department $department, User $user): Designation
    {
        if ($user->hasRole('Manager')) {
            return Designation::where('name', 'Production Supervisor')->firstOrFail();
        }

        if ($user->hasRole('HR Manager')) {
            return Designation::where('name', 'HR Manager')->firstOrFail();
        }

        if ($user->hasRole('HR')) {
            return Designation::where('name', 'HR Executive')->firstOrFail();
        }

        if ($user->hasRole('Sales Lead')) {
            return Designation::where('name', 'Regional Sales Manager')->firstOrFail();
        }

        if ($user->hasRole('Sales Rep')) {
            return Designation::where('name', 'Key Account Executive')->firstOrFail();
        }

        return $department->designations()->inRandomOrder()->firstOrFail();
    }
}
