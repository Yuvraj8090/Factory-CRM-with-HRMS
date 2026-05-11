<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\EmailLog;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmailLogSeeder extends Seeder
{
    public function run(): void
    {
        $leads = Lead::all();
        $customers = Customer::all();
        $salesUsers = User::role(['Sales Lead', 'Sales Rep', 'Manager'])->get();

        for ($i = 1; $i <= 10; $i++) {
            $isLead = $i <= 5;
            $target = $isLead ? $leads->random() : $customers->random();
            $status = fake()->randomElement(['Sent', 'Sent', 'Pending', 'Failed']);

            EmailLog::create([
                'lead_id' => $isLead ? $target->id : null,
                'customer_id' => $isLead ? null : $target->id,
                'subject' => fake()->sentence(5),
                'body' => fake()->paragraphs(2, true),
                'sent_to' => $target->email ?: fake()->safeEmail(),
                'sent_at' => $status === 'Pending' ? null : fake()->dateTimeBetween('-20 days', 'now'),
                'status' => $status,
                'error_message' => $status === 'Failed' ? 'SMTP timeout from demo gateway.' : null,
                'created_by' => $salesUsers->random()->id,
            ]);
        }
    }
}
