<?php

namespace Tests\Feature\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_their_bookings(): void
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = $customer->vehicles()->create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);

        $customer->bookings()->createMany([
            [
                'booking_code' => 'BK-000001',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-21',
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
                'status' => 'PENDING',
            ],
            [
                'booking_code' => 'BK-000002',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service CVT',
                'booking_date' => '2026-09-22',
                'booking_time' => '10:00:00',
                'complaint' => 'CVT berisik',
                'status' => 'CONFIRMED',
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.bookings.index'));

        $response->assertOk();

        $response->assertViewHas('bookings', function ($bookings) {
            return $bookings->count() === 2
                && $bookings->pluck('booking_code')->contains('BK-000001')
                && $bookings->pluck('booking_code')->contains('BK-000002');
        });
    }

    public function test_customer_can_view_booking_service_page(): void
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $customer->vehicles()->create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.bookingService'));

        $response->assertOk();

        $response->assertViewIs('customer.bookings.create');

        $response->assertViewHas('vehicles', function ($vehicles) {
            return $vehicles->count() === 1
                && $vehicles->first()->license_plate === 'B 1234 XYZ';
        });
    }

    public function test_customer_can_create_booking(): void
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = $customer->vehicles()->create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.store'), [
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-25',
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli dan cek rem',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => '2026-09-25',
            'booking_time' => '09:00:00',
            'complaint' => 'Ganti oli dan cek rem',
            'status' => 'PENDING',
        ]);

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_customer_cannot_create_booking_for_another_customers_vehicle(): void
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherCustomer = Customer::factory()->create();

        $otherVehicle = $otherCustomer->vehicles()->create([
            'license_plate' => 'B 5678 ABC',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => '2024',
            'color' => 'Blue',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.store'), [
                'vehicle_id' => $otherVehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-25',
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
            ]);

        $response->assertNotFound();

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_requires_required_fields(): void
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.store'), []);

        $response->assertSessionHasErrors([
            'vehicle_id',
            'service_type',
            'booking_date',
            'booking_time',
        ]);

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_code_is_generated_automatically(): void
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = $customer->vehicles()->create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);

        $this->actingAs($user)
            ->post(route('customer.bookings.store'), [
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-25',
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
            ]);

        $booking = $customer->bookings()->first();

        $this->assertNotNull($booking);
        $this->assertNotEmpty($booking->booking_code);

        $this->assertMatchesRegularExpression(
            '/^BK-\d{6}$/',
            $booking->booking_code
        );
    }

    public function test_customer_can_only_view_their_own_bookings(): void
    {
        $user = User::factory()->create([
            'role_id' => 5,
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherCustomer = Customer::factory()->create();

        $vehicle = $customer->vehicles()->create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);

        $otherVehicle = $otherCustomer->vehicles()->create([
            'license_plate' => 'B 5678 ABC',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => '2024',
            'color' => 'Blue',
        ]);

        $customer->bookings()->create([
            'booking_code' => 'BK-000001',
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => '2026-09-25',
            'booking_time' => '09:00:00',
            'complaint' => 'Ganti oli',
            'status' => 'PENDING',
        ]);

        $otherCustomer->bookings()->create([
            'booking_code' => 'BK-000002',
            'vehicle_id' => $otherVehicle->id,
            'service_type' => 'Service CVT',
            'booking_date' => '2026-09-26',
            'booking_time' => '10:00:00',
            'complaint' => 'CVT berisik',
            'status' => 'CONFIRMED',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.bookings.index'));

        $response->assertOk();

        $response->assertViewHas('bookings', function ($bookings) {
            return $bookings->count() === 1
                && $bookings->first()->booking_code === 'BK-000001';
        });
    }

    public function test_non_customer_cannot_access_customer_bookings(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.bookings.index'));

        $response->assertForbidden();
    }
}
