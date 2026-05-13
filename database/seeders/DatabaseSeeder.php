<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetTables();

        $this->call([
            LeadStageSeeder::class,
            ActivityDataSeeder::class,
            StateGstSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            SalesTeamSeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            LeaveTypeSeeder::class,
            CategorySeeder::class,
            ItemMasterSeeder::class,
            SalaryComponentSeeder::class,
            WhatsAppTemplateSeeder::class,
            CustomerSeeder::class,
            LeadSeeder::class,
            ActivitySeeder::class,
            EmployeeSeeder::class,
            AttendanceSeeder::class,
            LeaveRequestSeeder::class,
            EmployeeSalaryComponentSeeder::class,
            QuotationSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            DebitNoteSeeder::class,
            EmailLogSeeder::class,
            WhatsAppMessageSeeder::class,
            PayrollSeeder::class,
            CoreSystemTablesSeeder::class,
        ]);
    }

    private function resetTables(): void
    {
        $tables = [
            'cache_locks',
            'cache',
            'failed_jobs',
            'job_batches',
            'jobs',
            'sessions',
            'password_reset_tokens',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'permissions',
            'roles',
            'payroll_items',
            'payroll_periods',
            'employee_salary_components',
            'salary_components',
            'attendances',
            'leave_requests',
            'leave_types',
            'employees',
            'designations',
            'departments',
            'whats_app_messages',
            'whats_app_templates',
            'email_logs',
            'debit_note_items',
            'debit_notes',
            'payments',
            'invoice_items',
            'invoices',
            'quotation_items',
            'quotations',
            'item_masters',
            'categories',
            'activity_status_logs',
            'activities',
            'leads',
            'customers',
            'sales_teams',
            'users',
            'activity_statuses',
            'activity_types',
            'lead_stages',
            'state_gsts',
        ];

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }
}
