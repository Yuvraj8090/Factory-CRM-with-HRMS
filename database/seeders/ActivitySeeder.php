<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\ActivityType;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $types = ActivityType::all();
        $statuses = ActivityStatus::all()->keyBy('name');
        $leads = Lead::all();
        $salesUsers = User::role(['Sales Lead', 'Sales Rep'])->get();

        for ($i = 1; $i <= 20; $i++) {
            $lead = $leads->random();
            $statusName = fake()->randomElement(['Pending', 'In Progress', 'Completed', 'Cancelled', 'Rescheduled']);
            $dueDate = in_array($statusName, ['Completed', 'Cancelled'], true)
                ? fake()->dateTimeBetween('-20 days', '-1 day')
                : fake()->dateTimeBetween('-10 days', '+15 days');
            $completedAt = in_array($statusName, ['Completed', 'Cancelled'], true)
                ? fake()->dateTimeBetween($dueDate, '+4 days')
                : null;

            Activity::create([
                'lead_id' => $lead->id,
                'activity_type_id' => $types->random()->id,
                'activity_status_id' => $statuses[$statusName]->id,
                'assigned_to' => $lead->assigned_to ?: $salesUsers->random()->id,
                'subject' => fake()->sentence(4),
                'description' => fake()->paragraph(),
                'due_date' => $dueDate,
                'completed_at' => $completedAt,
                'created_by' => $salesUsers->random()->id,
            ]);
        }
    }
}
