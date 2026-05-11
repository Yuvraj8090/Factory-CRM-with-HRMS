<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\SalaryComponent;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayrollControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_hr_manager_can_generate_and_approve_payroll(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->seed(RolesAndPermissionsSeeder::class);

        $manager = User::factory()->create();
        $manager->assignRole('HR Manager');

        $employeeUser = User::factory()->create(['name' => 'Payroll Employee']);
        $department = Department::create(['name' => 'Production', 'description' => 'Plant operations', 'is_active' => true]);
        $designation = Designation::create(['name' => 'Operator', 'department_id' => $department->id]);
        $employee = Employee::create([
            'user_id' => $employeeUser->id,
            'employee_code' => 'EMP-1001',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'date_of_joining' => '2025-01-10',
            'salary' => 30000,
            'bank_name' => 'State Bank',
            'bank_account_number' => '1234567890',
            'ifsc_code' => 'SBIN0000123',
            'is_active' => true,
        ]);

        SalaryComponent::create([
            'name' => 'HRA',
            'code' => 'HRA',
            'type' => 'allowance',
            'calculation_type' => 'percentage',
            'default_amount' => 10,
            'is_taxable' => false,
            'is_active' => true,
        ]);

        SalaryComponent::create([
            'name' => 'Professional Tax',
            'code' => 'PTAX',
            'type' => 'tax',
            'calculation_type' => 'fixed',
            'default_amount' => 200,
            'is_taxable' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($manager)->post(route('hrms.payrolls.store'), [
            'name' => 'May 2026 Payroll',
            'period_start' => '2026-05-01',
            'period_end' => '2026-05-31',
            'payout_date' => '2026-06-02',
            'employee_ids' => [$employee->id],
        ]);

        $payroll = PayrollPeriod::first();
        $this->assertNotNull($payroll, 'Payroll was not created. Status: ' . $response->getStatusCode() . ' Location: ' . ($response->headers->get('Location') ?? 'none'));

        $response
            ->assertRedirect(route('hrms.payrolls.show', $payroll))
            ->assertSessionHas('status', 'Payroll generated successfully.');

        $this->assertDatabaseHas('payroll_items', [
            'payroll_period_id' => $payroll->id,
            'employee_id' => $employee->id,
            'basic_salary' => 30000,
            'gross_salary' => 33000,
            'total_taxes' => 200,
            'net_salary' => 32800,
        ]);

        $approveResponse = $this->actingAs($manager)->post(route('hrms.payrolls.approve', $payroll));

        $approveResponse
            ->assertRedirect()
            ->assertSessionHas('status', 'Payroll approved successfully.');

        $this->assertDatabaseHas('payroll_periods', [
            'id' => $payroll->id,
            'status' => 'approved',
        ]);
    }
}
