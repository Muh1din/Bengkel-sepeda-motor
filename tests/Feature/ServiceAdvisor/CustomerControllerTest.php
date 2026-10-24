<?php

namespace Tests\Feature\ServiceAdvisor;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $serviceAdvisor;

    protected function setUp(): void
    {
        parent::setUp();

        // Mengambil akun Service Advisor dari seeder.
        $this->serviceAdvisor = User::whereHas(
            'role',
            fn($query) => $query->where('name', 'ServiceAdvisor')
        )->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_open_customer_index(): void
    {
        $customer = Customer::query()->firstOrFail();

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.customer.index'));

        $response->assertOk();

        $response->assertViewIs('service-advisor.customer.index');

        $response->assertViewHas('customers');

        $response->assertViewHas(
            'customers',
            fn($customers) => $customers->contains('id', $customer->id)
        );
    }

    public function test_customer_index_loads_user_and_relationship_counts(): void
    {
        Customer::query()->firstOrFail();

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.customer.index'));

        $response->assertOk();

        $response->assertViewHas('customers', function ($customers) {
            $customer = $customers->first();

            if (!$customer) {
                return false;
            }

            return $customer->relationLoaded('user')
                && isset($customer->vehicles_count)
                && isset($customer->bookings_count);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_open_customer_detail(): void
    {
        $customer = Customer::query()->firstOrFail();

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.customer.show', $customer));

        $response->assertOk();

        $response->assertViewIs('service-advisor.customer.show');

        $response->assertViewHas(
            'customer',
            fn($viewCustomer) => $viewCustomer->id === $customer->id
        );
    }

    public function test_customer_detail_loads_required_relationships(): void
    {
        $customer = Customer::query()->firstOrFail();

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.customer.show', $customer));

        $response->assertOk();

        $response->assertViewHas('customer', function ($viewCustomer) {
            return $viewCustomer->relationLoaded('user')
                && $viewCustomer->relationLoaded('vehicles')
                && $viewCustomer->relationLoaded('bookings')
                && $viewCustomer->bookings->every(
                    fn($booking) => $booking->relationLoaded('workOrder')
                );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_open_customer_index(): void
    {
        Auth::logout();

        $response = $this->get(route('service-advisor.customer.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_open_customer_detail(): void
    {
        $customer = Customer::query()->firstOrFail();

        Auth::logout();

        $response = $this->get(
            route('service-advisor.customer.show', $customer)
        );

        $response->assertRedirect(route('login'));
    }
}
