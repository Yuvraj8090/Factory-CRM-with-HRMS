<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateGstSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $states = [
            ['state_name' => 'Jammu and Kashmir', 'state_code' => 'JK', 'gst_code' => '01', 'is_union_territory' => true],
            ['state_name' => 'Himachal Pradesh', 'state_code' => 'HP', 'gst_code' => '02', 'is_union_territory' => false],
            ['state_name' => 'Punjab', 'state_code' => 'PB', 'gst_code' => '03', 'is_union_territory' => false],
            ['state_name' => 'Chandigarh', 'state_code' => 'CH', 'gst_code' => '04', 'is_union_territory' => true],
            ['state_name' => 'Uttarakhand', 'state_code' => 'UK', 'gst_code' => '05', 'is_union_territory' => false],
            ['state_name' => 'Haryana', 'state_code' => 'HR', 'gst_code' => '06', 'is_union_territory' => false],
            ['state_name' => 'Delhi', 'state_code' => 'DL', 'gst_code' => '07', 'is_union_territory' => true],
            ['state_name' => 'Rajasthan', 'state_code' => 'RJ', 'gst_code' => '08', 'is_union_territory' => false],
            ['state_name' => 'Uttar Pradesh', 'state_code' => 'UP', 'gst_code' => '09', 'is_union_territory' => false],
            ['state_name' => 'Bihar', 'state_code' => 'BR', 'gst_code' => '10', 'is_union_territory' => false],
            ['state_name' => 'Sikkim', 'state_code' => 'SK', 'gst_code' => '11', 'is_union_territory' => false],
            ['state_name' => 'Arunachal Pradesh', 'state_code' => 'AR', 'gst_code' => '12', 'is_union_territory' => false],
            ['state_name' => 'Nagaland', 'state_code' => 'NL', 'gst_code' => '13', 'is_union_territory' => false],
            ['state_name' => 'Manipur', 'state_code' => 'MN', 'gst_code' => '14', 'is_union_territory' => false],
            ['state_name' => 'Mizoram', 'state_code' => 'MZ', 'gst_code' => '15', 'is_union_territory' => false],
            ['state_name' => 'Tripura', 'state_code' => 'TR', 'gst_code' => '16', 'is_union_territory' => false],
            ['state_name' => 'Meghalaya', 'state_code' => 'ML', 'gst_code' => '17', 'is_union_territory' => false],
            ['state_name' => 'Assam', 'state_code' => 'AS', 'gst_code' => '18', 'is_union_territory' => false],
            ['state_name' => 'West Bengal', 'state_code' => 'WB', 'gst_code' => '19', 'is_union_territory' => false],
            ['state_name' => 'Jharkhand', 'state_code' => 'JH', 'gst_code' => '20', 'is_union_territory' => false],
            ['state_name' => 'Odisha', 'state_code' => 'OD', 'gst_code' => '21', 'is_union_territory' => false],
            ['state_name' => 'Chhattisgarh', 'state_code' => 'CG', 'gst_code' => '22', 'is_union_territory' => false],
            ['state_name' => 'Madhya Pradesh', 'state_code' => 'MP', 'gst_code' => '23', 'is_union_territory' => false],
            ['state_name' => 'Gujarat', 'state_code' => 'GJ', 'gst_code' => '24', 'is_union_territory' => false],
            ['state_name' => 'Dadra and Nagar Haveli and Daman and Diu', 'state_code' => 'DNDD', 'gst_code' => '26', 'is_union_territory' => true],
            ['state_name' => 'Maharashtra', 'state_code' => 'MH', 'gst_code' => '27', 'is_union_territory' => false],
            ['state_name' => 'Andhra Pradesh', 'state_code' => 'AP', 'gst_code' => '28', 'is_union_territory' => false],
            ['state_name' => 'Karnataka', 'state_code' => 'KA', 'gst_code' => '29', 'is_union_territory' => false],
            ['state_name' => 'Goa', 'state_code' => 'GA', 'gst_code' => '30', 'is_union_territory' => false],
            ['state_name' => 'Lakshadweep', 'state_code' => 'LD', 'gst_code' => '31', 'is_union_territory' => true],
            ['state_name' => 'Kerala', 'state_code' => 'KL', 'gst_code' => '32', 'is_union_territory' => false],
            ['state_name' => 'Tamil Nadu', 'state_code' => 'TN', 'gst_code' => '33', 'is_union_territory' => false],
            ['state_name' => 'Puducherry', 'state_code' => 'PY', 'gst_code' => '34', 'is_union_territory' => true],
            ['state_name' => 'Andaman and Nicobar Islands', 'state_code' => 'AN', 'gst_code' => '35', 'is_union_territory' => true],
            ['state_name' => 'Telangana', 'state_code' => 'TS', 'gst_code' => '36', 'is_union_territory' => false],
            ['state_name' => 'Ladakh', 'state_code' => 'LA', 'gst_code' => '38', 'is_union_territory' => true],
            ['state_name' => 'Other Territory', 'state_code' => 'OT', 'gst_code' => '97', 'is_union_territory' => true],
            ['state_name' => 'Centre Jurisdiction', 'state_code' => 'CJ', 'gst_code' => '99', 'is_union_territory' => true],
        ];

        DB::table('state_gsts')->upsert(
            array_map(fn (array $state) => [
                ...$state,
                'created_at' => $now,
                'updated_at' => $now,
            ], $states),
            ['state_name'],
            ['state_code', 'gst_code', 'is_union_territory', 'updated_at']
        );
    }
}
