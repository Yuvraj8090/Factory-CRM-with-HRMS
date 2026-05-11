<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $states = DB::table('state_gsts')
            ->whereNotIn('state_name', ['Other Territory', 'Centre Jurisdiction'])
            ->get(['state_name', 'gst_code']);

        for ($i = 1; $i <= 10; $i++) {
            $state = $states->random();
            $pan = strtoupper(fake()->unique()->lexify('?????')) . str_pad((string) $i, 4, '0', STR_PAD_LEFT) . chr(64 + (($i % 26) + 1));
            $gst = $state->gst_code . $pan . '1Z' . ($i % 10);

            Customer::create([
                'name' => fake()->name(),
                'company_name' => fake()->company() . ' Foods',
                'email' => 'customer' . $i . '@example.test',
                'phone' => '98' . str_pad((string) (10000000 + $i), 8, '0', STR_PAD_LEFT),
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => $state->state_name,
                'country' => 'India',
                'pincode' => fake()->postcode(),
                'gst_number' => $i <= 8 ? $gst : null,
                'pan_number' => $i <= 8 ? $pan : null,
                'opening_balance' => fake()->randomFloat(2, 0, 25000),
                'credit_limit' => fake()->randomFloat(2, 50000, 500000),
                'customer_type' => fake()->randomElement(['retail', 'wholesale', 'institutional']),
                'status' => fake()->randomElement(['Active', 'Active', 'Active', 'Inactive', 'Blocked']),
            ]);
        }
    }
}
