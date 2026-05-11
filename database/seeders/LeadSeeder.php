<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $salesUsers = User::role(['Sales Lead', 'Sales Rep'])->get();
        $salesLeads = User::role('Sales Lead')->get();
        $customers = Customer::all();
        $wonStage = LeadStage::where('name', 'Won')->firstOrFail();
        $openStages = LeadStage::where('name', '!=', 'Won')->get();

        for ($i = 1; $i <= 10; $i++) {
            $assignedUser = $salesUsers->random();
            $isConverted = $i <= 3;

            Lead::create([
                'name' => fake()->name(),
                'company_name' => fake()->company() . ' Processors',
                'email' => 'lead' . $i . '@example.test',
                'phone' => '97' . str_pad((string) (20000000 + $i), 8, '0', STR_PAD_LEFT),
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'country' => 'India',
                'pincode' => fake()->postcode(),
                'lead_source' => fake()->randomElement(['Website', 'Trade Fair', 'Referral', 'Cold Call', 'WhatsApp Campaign']),
                'lead_stage_id' => $isConverted ? $wonStage->id : $openStages->random()->id,
                'assigned_to' => $assignedUser->id,
                'assigned_team_id' => $assignedUser->sales_team_id,
                'notes' => fake()->paragraph(),
                'is_converted' => $isConverted,
                'converted_customer_id' => $isConverted ? $customers->get($i - 1)?->id : null,
                'created_by' => $salesLeads->random()->id,
            ]);
        }
    }
}
