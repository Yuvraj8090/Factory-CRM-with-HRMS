<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LeadStageSeeder::class,
            ActivityDataSeeder::class,
            StateGstSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
