<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Default Demo User', 'email' => 'user@example.com', 'phone' => '9000000001', 'role' => 'Staff', 'password' => Hash::make('1234567890')],
            ['name' => 'System Administrator', 'email' => 'admin@factorycrm.test', 'phone' => '9000000002', 'role' => 'Super Admin', 'password' => Hash::make('password')],
            ['name' => 'Commercial Admin', 'email' => 'ops-admin@factorycrm.test', 'phone' => '9000000003', 'role' => 'Admin', 'password' => Hash::make('password')],
            ['name' => 'Operations Manager', 'email' => 'manager@factorycrm.test', 'phone' => '9000000004', 'role' => 'Manager', 'password' => Hash::make('password')],
            ['name' => 'HR Manager', 'email' => 'hr-manager@factorycrm.test', 'phone' => '9000000005', 'role' => 'HR Manager', 'password' => Hash::make('password')],
            ['name' => 'HR Executive', 'email' => 'hr-executive@factorycrm.test', 'phone' => '9000000006', 'role' => 'HR', 'password' => Hash::make('password')],
            ['name' => 'Sales Lead North', 'email' => 'saleslead.north@factorycrm.test', 'phone' => '9000000007', 'role' => 'Sales Lead', 'password' => Hash::make('password')],
            ['name' => 'Sales Lead West', 'email' => 'saleslead.west@factorycrm.test', 'phone' => '9000000008', 'role' => 'Sales Lead', 'password' => Hash::make('password')],
            ['name' => 'Sales Lead South', 'email' => 'saleslead.south@factorycrm.test', 'phone' => '9000000009', 'role' => 'Sales Lead', 'password' => Hash::make('password')],
            ['name' => 'Sales Lead East', 'email' => 'saleslead.east@factorycrm.test', 'phone' => '9000000010', 'role' => 'Sales Lead', 'password' => Hash::make('password')],
            ['name' => 'Sales Lead Institutional', 'email' => 'saleslead.enterprise@factorycrm.test', 'phone' => '9000000011', 'role' => 'Sales Lead', 'password' => Hash::make('password')],
            ['name' => 'Sales Representative A', 'email' => 'salesrep.a@factorycrm.test', 'phone' => '9000000012', 'role' => 'Sales Rep', 'password' => Hash::make('password')],
            ['name' => 'Sales Representative B', 'email' => 'salesrep.b@factorycrm.test', 'phone' => '9000000013', 'role' => 'Sales Rep', 'password' => Hash::make('password')],
            ['name' => 'Sales Representative C', 'email' => 'salesrep.c@factorycrm.test', 'phone' => '9000000014', 'role' => 'Sales Rep', 'password' => Hash::make('password')],
            ['name' => 'Factory Supervisor', 'email' => 'supervisor@factorycrm.test', 'phone' => '9000000015', 'role' => 'Staff', 'password' => Hash::make('password')],
            ['name' => 'Factory Operator', 'email' => 'operator@factorycrm.test', 'phone' => '9000000016', 'role' => 'Staff', 'password' => Hash::make('password')],
        ];

        foreach ($users as $attributes) {
            $role = $attributes['role'];
            unset($attributes['role']);

            $user = User::updateOrCreate(
                ['email' => $attributes['email']],
                array_merge($attributes, [
                    'email_verified_at' => now(),
                    'sales_team_id' => null,
                    'is_active' => true,
                    'remember_token' => Str::random(10),
                ])
            );

            $user->syncRoles([$role]);
        }

        $defaultUser = User::where('email', 'user@example.com')->firstOrFail();
        $defaultUser->syncPermissions([
            'view dashboard',
            'view attendances',
            'view leave-requests',
            'create leave-requests',
            'view customers',
        ]);
    }
}
