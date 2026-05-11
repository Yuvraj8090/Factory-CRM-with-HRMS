<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $employeeUsers = User::has('employee')->get();
        $leaveTypes = LeaveType::all();
        $approvers = User::role(['HR Manager', 'HR', 'Manager'])->get();

        for ($i = 1; $i <= 8; $i++) {
            $startDate = Carbon::now()->subDays(fake()->numberBetween(5, 45));
            $endDate = (clone $startDate)->addDays(fake()->numberBetween(1, 4));
            $status = fake()->randomElement(['Pending', 'Approved', 'Rejected']);

            LeaveRequest::create([
                'user_id' => $employeeUsers->random()->id,
                'leave_type_id' => $leaveTypes->random()->id,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_days' => $startDate->diffInDays($endDate) + 1,
                'reason' => fake()->sentence(10),
                'status' => $status,
                'approved_by' => $status === 'Pending' ? null : $approvers->random()->id,
            ]);
        }
    }
}
