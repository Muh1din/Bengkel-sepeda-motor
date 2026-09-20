<?php

namespace Tests\Feature\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    // Customer bisa melihat daftar kendaraannya
    public function test_customer_can_view_their_vehicles(): void
    {

        $user = User::factory()->create([
            'role_id' => 5,
        ]);

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

    // Customer bisa menambahkan kendaraan
    public function test_customer_can_add_a_vehicle(): void
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

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

    // Customer bisa membuka halaman edit kendaraan
    public function test_customer_can_view_vehicle_edit_page(): void
    {

        $user = User::factory()->create([
            'role_id' => 5,
        ]);

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

    // Customer bisa membuka halaman edit kendaraan
    public function test_customer_can_update_their_vehicle(): void
    {

        $user = User::factory()->create([
            'role_id' => 5,
        ]);

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

        $response->assertRedirect('/customer/vehicles');

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

    // Customer tidak bisa membuka halaman edit kendaraan customer lain
    public function test_customer_cannot_edit_another_customers_vehicle(): void
    {

        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
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

    // Customer tidak bisa mengubah kendaraan customer lain
    public function test_customer_cannot_update_another_customers_vehicle(): void
    {

        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
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

    // Customer bisa menghapus kendaraan miliknya
    public function test_customer_can_delete_their_vehicle(): void
    {

        $user = User::factory()->create([
            'role_id' => 5,
        ]);

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
            ->delete(
                route('customer.vehicles.destroy', $vehicle->id)
            );

        $response->assertRedirect('/customer/vehicles');

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
        ]);
    }

    // Customer tidak bisa menghapus kendaraan customer lain
    public function test_customer_cannot_delete_another_customers_vehicle(): void
    {

        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
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
