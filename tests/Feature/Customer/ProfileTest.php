<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat user Customer yang aktif.
     */
    private function createActiveCustomerUser(): User
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $user->forceFill([
            'is_active' => true,
        ])->save();

        return $user;
    }

    /**
     * Membuat Customer yang terhubung dengan User.
     */
    private function createCustomer(User $user): Customer
    {
        return Customer::factory()->create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => 'Jakarta Selatan',
        ]);
    }

    /**
     * Customer bisa melihat halaman profil.
     */
    public function test_customer_can_view_their_profile(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.profile'));

        $response->assertOk();

        $response->assertViewHas(
            'customer',
            fn($viewCustomer) => $viewCustomer->id === $customer->id
        );
    }

    /**
     * Customer bisa membuka halaman edit profil.
     */
    public function test_customer_can_view_profile_edit_page(): void
    {
        $user = $this->createActiveCustomerUser();

        $this->createCustomer($user);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.profile.edit'));

        $response->assertOk();
    }

    /**
     * Customer bisa memperbarui profil.
     */
    public function test_customer_can_update_their_profile(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);

        $updatedData = [
            'name' => 'Didin Updated',
            'email' => 'didin.updated@example.com',
            'phone' => '089999000001',
            'address' => 'Bandung',
        ];

        $response = $this
            ->actingAs($user)
            ->put(
                route('customer.profile.update'),
                $updatedData
            );

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(
            route('customer.profile')
        );

        $response->assertSessionHas(
            'success',
            'Profile updated successfully.'
        );

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $updatedData['name'],
            'email' => $updatedData['email'],
            'phone' => $updatedData['phone'],
            'address' => $updatedData['address'],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $updatedData['name'],
            'email' => $updatedData['email'],
            'phone' => $updatedData['phone'],
        ]);
    }

    /**
     * Update profil ditolak jika nama tidak diisi.
     */
    public function test_customer_cannot_update_profile_without_name(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);

        $response = $this
            ->actingAs($user)
            ->put(
                route('customer.profile.update'),
                [
                    'name' => '',
                    'email' => 'new.email@example.com',
                    'phone' => '081234567890',
                    'address' => 'Bandung',
                ]
            );

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => $customer->address,
        ]);
    }

    /**
     * Update profil ditolak jika format email tidak valid.
     */
    public function test_customer_cannot_update_profile_with_invalid_email(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);

        $response = $this
            ->actingAs($user)
            ->put(
                route('customer.profile.update'),
                [
                    'name' => 'Didin Updated',
                    'email' => 'invalid-email',
                    'phone' => '081234567890',
                    'address' => 'Bandung',
                ]
            );

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Update profil ditolak jika email digunakan Customer lain.
     */
    public function test_customer_cannot_use_another_customers_email(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);

        $anotherCustomer = Customer::factory()->create([
            'email' => 'taken@example.com',
            'phone' => '089876543210',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(
                route('customer.profile.update'),
                [
                    'name' => 'Didin Updated',
                    'email' => $anotherCustomer->email,
                    'phone' => '081234567890',
                    'address' => 'Bandung',
                ]
            );

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'email' => $user->email,
        ]);
    }

    /**
     * Update profil ditolak jika nomor telepon digunakan Customer lain.
     */
    public function test_customer_cannot_use_another_customers_phone(): void
    {
        $user = $this->createActiveCustomerUser();
        $customer = $this->createCustomer($user);

        $anotherCustomer = Customer::factory()->create([
            'email' => 'another@example.com',
            'phone' => '089876543210',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(
                route('customer.profile.update'),
                [
                    'name' => 'Didin Updated',
                    'email' => 'didin.updated@example.com',
                    'phone' => $anotherCustomer->phone,
                    'address' => 'Bandung',
                ]
            );

        $response->assertSessionHasErrors('phone');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'phone' => $user->phone,
        ]);
    }
}
