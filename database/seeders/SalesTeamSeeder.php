<?php

namespace Database\Seeders;

use App\Models\SalesTeam;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalesTeamSeeder extends Seeder
{
    public function run(): void
    {
        $teamDefinitions = [
            ['name' => 'North Channel Team', 'description' => 'Handles distributors and wholesalers across North India.'],
            ['name' => 'West Retail Team', 'description' => 'Focuses on urban retail chains and modern trade in the West zone.'],
            ['name' => 'South Key Accounts', 'description' => 'Owns large food processing and institutional accounts in South India.'],
            ['name' => 'East Growth Desk', 'description' => 'Builds dealer and project business across East and North-East markets.'],
            ['name' => 'Institutional Solutions Team', 'description' => 'Manages tenders, B2B projects, and enterprise procurement cycles.'],
        ];

        $salesLeads = User::role('Sales Lead')->orderBy('id')->get()->values();
        $salesReps = User::role('Sales Rep')->orderBy('id')->get()->values();

        foreach ($teamDefinitions as $index => $definition) {
            $teamLead = $salesLeads->get($index);

            $team = SalesTeam::create([
                'name' => $definition['name'],
                'team_lead_id' => $teamLead?->id,
                'description' => $definition['description'],
                'is_active' => true,
            ]);

            $members = collect([
                $teamLead,
                $salesReps->get($index % max(1, $salesReps->count())),
                $salesReps->get(($index + 1) % max(1, $salesReps->count())),
            ])->filter();

            foreach ($members as $member) {
                $member->update(['sales_team_id' => $team->id]);
            }
        }
    }
}
