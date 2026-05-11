<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Casual Leave', 'leave_days_per_year' => 12, 'is_paid' => true],
            ['name' => 'Sick Leave', 'leave_days_per_year' => 10, 'is_paid' => true],
            ['name' => 'Earned Leave', 'leave_days_per_year' => 18, 'is_paid' => true],
            ['name' => 'Maternity Leave', 'leave_days_per_year' => 180, 'is_paid' => true],
            ['name' => 'Paternity Leave', 'leave_days_per_year' => 10, 'is_paid' => true],
            ['name' => 'Loss of Pay', 'leave_days_per_year' => 30, 'is_paid' => false],
        ];

        foreach ($types as $type) {
            LeaveType::create($type);
        }
    }
}
