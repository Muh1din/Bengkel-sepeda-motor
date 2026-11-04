<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register(): void
    {
        $customerRole = Role::where('name', 'Customer')->firstOrFail();
        $adminRole = Role::where('name', 'Admin')->firstOrFail();

        $response = $this->post(route('register.store'), [
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'phone' => '085717676292',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role_id' => $adminRole->id,
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
        $customerRole = Role::where('name', 'Customer')->firstOrFail();

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

    public function test_customer_cannot_register_with_email_used_by_guest_customer(): void
    {
        Customer::create([
            'name' => 'Guest Customer',
            'email' => 'guest@example.com',
            'phone' => '081234567899',
            'user_id' => null,
        ]);

        $response = $this->post(route('register.store'), [
            'name' => 'Registered Customer',
            'email' => 'guest@example.com',
            'phone' => '081234567898',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', [
            'email' => 'guest@example.com',
        ]);
    }

    public function test_password_confirmation_must_match(): void
    {
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

    public function test_customer_can_register_without_a_phone_number(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Budi Social',
            'email' => 'budi.social@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));

        $user = User::where('email', 'budi.social@example.com')->firstOrFail();

        $this->assertNull($user->phone);
        $this->assertSame('Customer', $user->role->name);
        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'email' => 'budi.social@example.com',
            'phone' => null,
        ]);
    }

    public function test_multiple_customers_can_register_without_phone_numbers(): void
    {
        foreach (['first', 'second'] as $customer) {
            $this->post(route('register.store'), [
                'name' => ucfirst($customer),
                'email' => $customer.'.social@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])->assertRedirect(route('login'));
        }

        $this->assertSame(
            2,
            User::whereIn('email', [
                'first.social@example.com',
                'second.social@example.com',
            ])->whereNull('phone')->count()
        );

        $this->assertSame(
            2,
            Customer::whereIn('email', [
                'first.social@example.com',
                'second.social@example.com',
            ])->whereNull('phone')->count()
        );
    }

    public function test_registration_accepts_email_that_exceeds_legacy_customer_column_length(): void
    {
        $email = 'social.'.str_repeat('customer', 8).'@example.com';
        $name = trim('Social Customer '.str_repeat('Long Name ', 12));

        $this->post(route('register.store'), [
            'name' => $name,
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('customers', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function test_registration_rolls_back_user_when_customer_creation_fails(): void
    {
        Customer::creating(function (Customer $customer): void {
            if ($customer->user_id !== null) {
                throw new RuntimeException('Customer profile could not be created.');
            }
        });

        $this->withoutExceptionHandling();

        try {
            $this->post(route('register.store'), [
                'name' => 'Budi',
                'email' => 'transaction@example.com',
                'phone' => '081234567891',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            $this->fail('Registration should fail when customer creation fails.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Customer profile could not be created.', $exception->getMessage());
        }

        $this->assertDatabaseMissing('users', [
            'email' => 'transaction@example.com',
        ]);
    }

    public function test_registration_is_throttled_after_five_attempts_from_the_same_ip(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post(route('register.store'), [])
                ->assertSessionHasErrors(['name', 'email', 'password']);
        }

        $this->post(route('register.store'), [])
            ->assertStatus(429);
    }
}
