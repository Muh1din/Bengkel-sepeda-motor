<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(String $roleName): User
    {
        $role = Role::create([
            'name' => $roleName
        ]);

        return User::factory()->create([
            'role_id' => $role->id
        ]);
    }

    public function test_owner_can_access_owner_dashboard(): void
    {
        $user = $this->createUser('Owner');

        $response = $this->actingAs($user)
            ->get('/owner/dashboard');

        $response->assertOk();
    }

    public function test_owner_can_access_service_advisor_dashboard(): void
    {
        $user = $this->createUser('Owner');

        $response = $this->actingAs($user)
            ->get('/service-advisor/dashboard');

        $response->assertForbidden();
    }

    public function test_mechanic_can_access_mechanic_dashboard(): void
    {
        $user = $this->createUser('Mechanic');

        $response = $this->actingAs($user)
            ->get('/mechanic/dashboard');

        $response->assertOk();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $user = $this->createUser('Admin');

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_customer_can_access_customer_dashboard(): void
    {
        $user = $this->createUser('Customer');

        $response = $this->actingAs($user)
            ->get('/customer/dashboard');
            
        $response->assertOk();
    }

    public function test_guest_cannot_access_owner_dashboard(): void
    {
        $response = $this->get('/owner/dashboard');
        $response->assertRedirect(route('login'));
    }
}
