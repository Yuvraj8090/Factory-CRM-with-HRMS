<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_lead_can_create_customer_from_html_flow(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Sales Lead');

        $response = $this->actingAs($user)->post(route('crm.customers.store'), [
            'name' => 'Fresh Foods Buyer',
            'company_name' => 'Fresh Foods Pvt Ltd',
            'email' => 'buyer@example.test',
            'phone' => '9999999999',
            'address' => 'Plant Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'country' => 'India',
            'pincode' => '302001',
            'customer_type' => 'wholesale',
            'status' => 'Active',
            'opening_balance' => 0,
            'credit_limit' => 50000,
        ]);

        $customer = Customer::first();
        $this->assertNotNull($customer, 'Customer was not created. Status: ' . $response->getStatusCode() . ' Location: ' . ($response->headers->get('Location') ?? 'none'));

        $response
            ->assertRedirect(route('crm.customers.show', $customer))
            ->assertSessionHas('status', 'Customer created successfully.');

        $this->assertDatabaseHas('customers', [
            'name' => 'Fresh Foods Buyer',
            'customer_type' => 'wholesale',
            'status' => 'Active',
        ]);
    }
}
