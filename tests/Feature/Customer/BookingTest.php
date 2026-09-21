<?php

namespace Tests\Feature\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\Booking;
use App\Models\Vehicle;
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

    public function test_customer_can_cancel_pending_booking(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-123456',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => 'PENDING',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.cancel', $booking->id), [
                'cancellation_reason' => 'Ada keperluan mendadak.',
            ]);

        $response->assertRedirect(route('customer.bookings.index'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'CANCELLED',
            'cancellation_reason' => 'Ada keperluan mendadak.',
        ]);
    }

    public function test_customer_cannot_cancel_booking_without_reason(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-123456',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => 'PENDING',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.cancel', $booking->id), [
                'cancellation_reason' => '',
            ]);

        $response->assertSessionHasErrors('cancellation_reason');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_customer_cannot_cancel_confirmed_booking(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-123456',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => 'CONFIRMED',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.cancel', $booking->id), [
                'cancellation_reason' => 'Saya berubah jadwal.',
            ]);

        $response->assertRedirect(route('customer.bookings.index'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'CONFIRMED',
            'cancellation_reason' => null,
        ]);
    }

    public function test_customer_cannot_cancel_another_customer_booking(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $otherUser = User::factory()->create([
            'role_id' => $role->id,
        ]);

        Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $otherCustomer = Customer::create([
            'name' => $otherUser->name,
            'email' => $otherUser->email,
            'phone' => $otherUser->phone,
            'user_id' => $otherUser->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 5678 ABC',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $otherCustomer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-654321',
            'customer_id' => $otherCustomer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin bermasalah',
            'status' => 'PENDING',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.cancel', $booking->id), [
                'cancellation_reason' => 'Mencoba membatalkan booking orang lain.',
            ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'PENDING',
            'cancellation_reason' => null,
        ]);
    }

    public function test_canceled_booking_does_not_appear_in_active_bookings(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);

        Booking::create([
            'booking_code' => 'BK-123456',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => 'CANCELLED',
            'cancellation_reason' => 'Ada keperluan mendadak.',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.bookings.index'));

        $response->assertDontSee('BK-123456');
    }

    public function test_customer_can_review_completed_booking(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-123456',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => 'COMPLETED',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(
                route('customer.bookings.review.store', $booking->id),
                [
                    'rating' => 5,
                    'comment' => 'Pelayanannya sangat baik.',
                ]
            );

        $response->assertRedirect(route('customer.riwayatService'));

        $this->assertDatabaseHas('reviews', [
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Pelayanannya sangat baik.',
        ]);
    }

    public function test_customer_cannot_review_non_completed_booking(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-123456',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => 'PENDING',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(
                route('customer.bookings.review.store', $booking->id),
                [
                    'rating' => 5,
                    'comment' => 'Pelayanannya sangat baik.',
                ]
            );

        $response->assertRedirect(route('customer.riwayatService'));

        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }

    public function test_customer_cannot_review_booking_twice(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-123456',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => 'COMPLETED',
        ]);

        $booking->review()->create([
            'customer_id' => $customer->id,
            'rating' => 4,
            'comment' => 'Pelayanan cukup baik.',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(
                route('customer.bookings.review.store', $booking->id),
                [
                    'rating' => 5,
                    'comment' => 'Saya mencoba memberikan review kedua.',
                ]
            );

        $response->assertRedirect(route('customer.riwayatService'));

        $response->assertSessionHas('error');

        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_review_rating_must_be_between_one_and_five(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-123456',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => 'COMPLETED',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(
                route('customer.bookings.review.store', $booking->id),
                [
                    'rating' => 6,
                    'comment' => 'Rating tidak valid.',
                ]
            );

        $response->assertSessionHasErrors('rating');

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }

    public function test_customer_cannot_review_another_customer_booking(): void
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $otherUser = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);

        $otherCustomer = Customer::create([
            'name' => $otherUser->name,
            'email' => $otherUser->email,
            'phone' => $otherUser->phone,
            'user_id' => $otherUser->id,
        ]);

        $vehicle = Vehicle::create([
            'license_plate' => 'B 5678 ABC',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $otherCustomer->id,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-654321',
            'customer_id' => $otherCustomer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin bermasalah',
            'status' => 'COMPLETED',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(
                route('customer.bookings.review.store', $booking->id),
                [
                    'rating' => 5,
                    'comment' => 'Mencoba review booking customer lain.',
                ]
            );

        $response->assertNotFound();

        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id,
        ]);
    }
}
