<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppTemplate;
use Illuminate\Database\Seeder;

class WhatsAppMessageSeeder extends Seeder
{
    public function run(): void
    {
        $templates = WhatsAppTemplate::all();
        $leads = Lead::all();
        $customers = Customer::all();
        $users = User::role(['Sales Lead', 'Sales Rep', 'Manager'])->get();

        for ($i = 1; $i <= 10; $i++) {
            $useLead = $i % 2 === 1;
            $target = $useLead ? $leads->random() : $customers->random();
            $status = fake()->randomElement(['Queued', 'Sent', 'Delivered', 'Failed']);

            WhatsAppMessage::create([
                'template_id' => $templates->random()->id,
                'lead_id' => $useLead ? $target->id : null,
                'customer_id' => $useLead ? null : $target->id,
                'phone' => $target->phone ?: ('96' . str_pad((string) (30000000 + $i), 8, '0', STR_PAD_LEFT)),
                'message_body' => fake()->sentence(12),
                'status' => $status,
                'sent_at' => $status === 'Queued' ? null : fake()->dateTimeBetween('-15 days', 'now'),
                'response_payload' => [
                    'provider' => 'demo-gateway',
                    'message_id' => 'MSG-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'status' => $status,
                ],
                'created_by' => $users->random()->id,
            ]);
        }
    }
}
