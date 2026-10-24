<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
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

        // Pastikan is_active tersimpan meskipun tidak ada di $fillable.
        $user->forceFill([
            'is_active' => true,
        ])->save();

        return $user;
    }

    /**
     * Customer bisa melihat daftar kendaraannya.
     */
    public function test_customer_can_view_their_vehicles(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        Vehicle::factory()->create([
            'customer_id' => $customer->id,
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.vehicles.index'));

        $response->assertOk();

        $response->assertSee('B 1234 XYZ');
        $response->assertSee('Honda');
        $response->assertSee('Vario 160');
    }

    /**
     * Customer bisa menambahkan kendaraan.
     */
    public function test_customer_can_add_a_vehicle(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('customer.vehicles.store'), [
                'license_plate' => 'B 5678 ABC',
                'brand' => 'Yamaha',
                'model' => 'NMAX',
                'year' => 2025,
                'color' => 'Black',
            ]);

        $response->assertRedirect(
            route('customer.vehicles.index')
        );

        $this->assertDatabaseHas('vehicles', [
            'customer_id' => $customer->id,
            'license_plate' => 'B 5678 ABC',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2025,
            'color' => 'Black',
        ]);
    }

    /**
     * Customer bisa membuka halaman edit kendaraannya.
     */
    public function test_customer_can_view_vehicle_edit_page(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::factory()->create([
            'customer_id' => $customer->id,
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.vehicles.edit', $vehicle->id));

        $response->assertOk();

        $response->assertSee('B 1234 XYZ');
        $response->assertSee('Honda');
        $response->assertSee('Vario 160');
        $response->assertSee('2024');
        $response->assertSee('Black');
    }

    /**
     * Customer bisa memperbarui kendaraannya.
     */
    public function test_customer_can_update_their_vehicle(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::factory()->create([
            'customer_id' => $customer->id,
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(
                route('customer.vehicles.update', $vehicle->id),
                [
                    'license_plate' => 'B 5678 ABC',
                    'brand' => 'Yamaha',
                    'model' => 'NMAX',
                    'year' => '2025',
                    'color' => 'White',
                ]
            );

        $response->assertRedirect(
            route('customer.vehicles.index')
        );

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'customer_id' => $customer->id,
            'license_plate' => 'B 5678 ABC',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => '2025',
            'color' => 'White',
        ]);
    }

    /**
     * Customer tidak bisa membuka halaman edit kendaraan customer lain.
     */
    public function test_customer_cannot_edit_another_customers_vehicle(): void
    {
        $user = $this->createActiveCustomerUser();

        Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $anotherCustomer = Customer::factory()->create();

        $vehicle = Vehicle::factory()->create([
            'customer_id' => $anotherCustomer->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.vehicles.edit', $vehicle->id));

        $response->assertNotFound();
    }

    /**
     * Customer tidak bisa memperbarui kendaraan customer lain.
     */
    public function test_customer_cannot_update_another_customers_vehicle(): void
    {
        $user = $this->createActiveCustomerUser();

        Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $anotherCustomer = Customer::factory()->create();

        $vehicle = Vehicle::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(
                route('customer.vehicles.update', $vehicle->id),
                [
                    'license_plate' => 'B 5678 ABC',
                    'brand' => 'Yamaha',
                    'model' => 'NMAX',
                    'year' => '2025',
                    'color' => 'White',
                ]
            );

        $response->assertNotFound();

        // Pastikan data kendaraan milik customer lain tidak berubah.
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'customer_id' => $anotherCustomer->id,
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);
    }

    /**
     * Customer bisa menghapus kendaraannya.
     */
    public function test_customer_can_delete_their_vehicle(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::factory()->create([
            'customer_id' => $customer->id,
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(
                route('customer.vehicles.destroy', $vehicle->id)
            );

        $response->assertRedirect(
            route('customer.vehicles.index')
        );

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
        ]);
    }

    /**
     * Customer tidak bisa menghapus kendaraan customer lain.
     */
    public function test_customer_cannot_delete_another_customers_vehicle(): void
    {
        $user = $this->createActiveCustomerUser();

        Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $anotherCustomer = Customer::factory()->create();

        $vehicle = Vehicle::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(
                route('customer.vehicles.destroy', $vehicle->id)
            );

        $response->assertNotFound();

        // Kendaraan customer lain harus tetap ada.
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'customer_id' => $anotherCustomer->id,
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
        ]);
    }
}
