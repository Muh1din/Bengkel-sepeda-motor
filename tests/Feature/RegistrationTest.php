<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register(): void
    {
        $customerRole = Role::create([
            'name' => 'customer'
        ]);

        $response = $this->post(route('register.store'), [
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'phone' => '085717676292',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'phone' => '085717676292',
            'role_id' => $customerRole->id,
        ]);

        $user = User::where('email', 'budi@gmail.com')->first();

        $this->assertDatabaseHas('customers', [
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'phone' => '085717676292',
            'user_id' => $user->id,
        ]);
    }


    public function test_customer_cannot_register_with_existing_email(): void
    {
        $customerRole = Role::create([
            'name' => 'customer',
        ]);

        User::create([
            'name' => 'Existing User',
            'email' => 'budi@gmail.com',
            'phone' => '081111111111',
            'password' => bcrypt('password123'),
            'role_id' => $customerRole->id,
        ]);

        $response = $this->post(route('register.store'), [
            'name' => 'Budi Baru',
            'email' => 'budi@gmail.com',
            'phone' => '085717676292',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('customers', [
            'email' => 'budi@gmail.com',
        ]);
    }

    public function test_password_confirmation_must_match(): void
    {
        Role::create([
            'name' => 'customer',
        ]);

        $response = $this->post(route('register.store'), [
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'phone' => '085717676292',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'email' => 'budi@gmail.com',
        ]);
    }
}
