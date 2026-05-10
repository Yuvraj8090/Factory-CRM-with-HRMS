<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadStageSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $stages = [
            ['name' => 'New', 'stage_order' => 1, 'is_default' => true],
            ['name' => 'Contacted', 'stage_order' => 2, 'is_default' => false],
            ['name' => 'Qualified', 'stage_order' => 3, 'is_default' => false],
            ['name' => 'Proposal Sent', 'stage_order' => 4, 'is_default' => false],
            ['name' => 'Negotiation', 'stage_order' => 5, 'is_default' => false],
            ['name' => 'Won', 'stage_order' => 6, 'is_default' => false],
            ['name' => 'Lost', 'stage_order' => 7, 'is_default' => false],
        ];

        DB::table('lead_stages')->upsert(
            array_map(fn (array $stage) => [
                ...$stage,
                'created_at' => $now,
                'updated_at' => $now,
            ], $stages),
            ['name'],
            ['stage_order', 'is_default', 'updated_at']
        );
    }
}
