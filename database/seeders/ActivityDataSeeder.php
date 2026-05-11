<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $types = [
            ['name' => 'Call', 'description' => 'Inbound or outbound phone conversation with a lead or customer.'],
            ['name' => 'Email', 'description' => 'Email communication, follow-up, or documentation touchpoint.'],
            ['name' => 'Meeting', 'description' => 'Physical or virtual meeting, demo, or plant visit.'],
            ['name' => 'Site Visit', 'description' => 'On-site visit to a customer plant, warehouse, or office.'],
            ['name' => 'WhatsApp', 'description' => 'Instant messaging update or conversation through WhatsApp.'],
            ['name' => 'Quotation Follow-up', 'description' => 'Commercial follow-up after sharing a quotation or invoice.'],
        ];

        DB::table('activity_types')->upsert(
            array_map(fn (array $type) => [
                ...$type,
                'created_at' => $now,
                'updated_at' => $now,
            ], $types),
            ['name'],
            ['description', 'updated_at']
        );

        $statuses = [
            ['name' => 'Pending', 'color' => '#F59E0B'],
            ['name' => 'In Progress', 'color' => '#3B82F6'],
            ['name' => 'Completed', 'color' => '#10B981'],
            ['name' => 'Cancelled', 'color' => '#EF4444'],
            ['name' => 'Rescheduled', 'color' => '#8B5CF6'],
        ];

        DB::table('activity_statuses')->upsert(
            array_map(fn (array $status) => [
                ...$status,
                'created_at' => $now,
                'updated_at' => $now,
            ], $statuses),
            ['name'],
            ['color', 'updated_at']
        );
    }
}
