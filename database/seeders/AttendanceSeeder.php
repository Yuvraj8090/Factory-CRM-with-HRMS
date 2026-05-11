<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::with('user')->get();
        $creatorId = User::role(['HR Manager', 'HR', 'Manager'])->inRandomOrder()->value('id');
        $dates = collect(range(0, 4))->map(fn (int $offset) => Carbon::now()->subWeekdays($offset))->reverse();

        foreach ($employees as $employee) {
            foreach ($dates as $date) {
                $status = fake()->randomElement(['Present', 'Present', 'Present', 'Late', 'Half Day', 'Leave', 'Absent']);
                $attendance = new Attendance([
                    'user_id' => $employee->user_id,
                    'date' => $date->toDateString(),
                    'status' => $status,
                    'notes' => $status === 'Present' ? null : fake()->sentence(),
                    'created_by' => $creatorId,
                ]);

                if (in_array($status, ['Present', 'Late', 'Half Day'], true)) {
                    $checkIn = match ($status) {
                        'Late' => '10:15:00',
                        'Half Day' => '09:30:00',
                        default => '09:05:00',
                    };

                    $checkOut = match ($status) {
                        'Half Day' => '14:00:00',
                        default => fake()->randomElement(['18:10:00', '18:25:00', '19:00:00']),
                    };

                    $attendance->check_in = $checkIn;
                    $attendance->check_out = $checkOut;
                    $attendance->calculateHours('18:00:00');
                }

                $attendance->save();
            }
        }
    }
}
