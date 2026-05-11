<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@factorycrm.test',
                'phone' => '9999000001',
                'is_active' => true,
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Commercial Admin',
                'email' => 'ops-admin@factorycrm.test',
                'phone' => '9999000002',
                'is_active' => true,
                'role' => 'Admin',
            ],
            [
                'name' => 'Operations Manager',
                'email' => 'manager@factorycrm.test',
                'phone' => '9999000003',
                'is_active' => true,
                'role' => 'Manager',
            ],
            [
                'name' => 'HR Manager',
                'email' => 'hr@factorycrm.test',
                'phone' => '9999000004',
                'is_active' => true,
                'role' => 'HR Manager',
            ],
            [
                'name' => 'Sales Lead',
                'email' => 'saleslead@factorycrm.test',
                'phone' => '9999000005',
                'is_active' => true,
                'role' => 'Sales Lead',
            ],
            [
                'name' => 'Sales Representative',
                'email' => 'salesrep@factorycrm.test',
                'phone' => '9999000006',
                'is_active' => true,
                'role' => 'Sales Rep',
            ],
            [
                'name' => 'Factory Staff',
                'email' => 'staff@factorycrm.test',
                'phone' => '9999000007',
                'is_active' => true,
                'role' => 'Staff',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'email_verified_at' => now(),
                    'password' => 'password',
                ])
            );

            $user->syncRoles([$role]);
        }
    }
}
