<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $modulePermissions = [
            'activities',
            'leads',
            'sales-teams',
            'customers',
            'categories',
            'item-masters',
            'quotations',
            'invoices',
            'debit-notes',
            'payments',
            'whats-app-templates',
            'attendances',
            'departments',
            'designations',
            'employees',
            'leave-types',
            'leave-requests',
        ];

        $permissions = ['view dashboard', 'manage roles and permissions'];

        foreach ($modulePermissions as $module) {
            foreach (['view', 'create', 'update', 'delete'] as $action) {
                $permissions[] = "{$action} {$module}";
            }
        }

        $permissions = array_merge($permissions, [
            'convert leads',
            'import leads',
            'export leads',
            'send lead communication',
            'send customer communication',
            'import attendances',
            'export attendances',
        ]);

        foreach (array_unique($permissions) as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $allPermissions = Permission::pluck('name')->all();

        $superAdmin = Role::findOrCreate('Super Admin', 'web');
        $superAdmin->syncPermissions($allPermissions);

        $hrManagerPermissions = [
            'view dashboard',
            'view attendances',
            'create attendances',
            'update attendances',
            'delete attendances',
            'import attendances',
            'export attendances',
            'view departments',
            'create departments',
            'update departments',
            'delete departments',
            'view designations',
            'create designations',
            'update designations',
            'delete designations',
            'view employees',
            'create employees',
            'update employees',
            'delete employees',
            'view leave-types',
            'create leave-types',
            'update leave-types',
            'delete leave-types',
            'view leave-requests',
            'create leave-requests',
            'update leave-requests',
            'delete leave-requests',
            'view customers',
        ];

        Role::findOrCreate('HR Manager', 'web')->syncPermissions($hrManagerPermissions);

        $salesLeadPermissions = [
            'view dashboard',
            'view activities',
            'create activities',
            'update activities',
            'delete activities',
            'view leads',
            'create leads',
            'update leads',
            'delete leads',
            'convert leads',
            'import leads',
            'export leads',
            'send lead communication',
            'view sales-teams',
            'create sales-teams',
            'update sales-teams',
            'view customers',
            'create customers',
            'update customers',
            'send customer communication',
            'view quotations',
            'create quotations',
            'update quotations',
            'delete quotations',
            'view invoices',
            'create invoices',
            'update invoices',
            'view payments',
            'create payments',
        ];

        Role::findOrCreate('Sales Lead', 'web')->syncPermissions($salesLeadPermissions);

        $salesRepPermissions = [
            'view dashboard',
            'view activities',
            'create activities',
            'update activities',
            'view leads',
            'create leads',
            'update leads',
            'import leads',
            'export leads',
            'send lead communication',
            'view customers',
            'create customers',
            'update customers',
            'send customer communication',
            'view quotations',
            'create quotations',
            'update quotations',
            'view invoices',
        ];

        Role::findOrCreate('Sales Rep', 'web')->syncPermissions($salesRepPermissions);
    }
}
