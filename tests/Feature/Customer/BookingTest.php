<?php

namespace Tests\Feature\Customer;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat user dengan status aktif.
     */
    private function createActiveUser(int $roleId): User
    {
        $user = User::factory()->create([
            'role_id' => $roleId,
        ]);

        // Pastikan atribut is_active tersimpan meskipun tidak
        // tercantum dalam daftar fillable model User.
        $user->forceFill([
            'is_active' => true,
        ])->save();

        return $user;
    }

    /**
     * Membuat user dengan role Customer.
     */
    private function createCustomerUser(): User
    {
        $role = Role::create([
            'name' => 'Customer',
        ]);

        return $this->createActiveUser($role->id);
    }

    /**
     * Membuat data customer yang terhubung ke user.
     */
    private function createCustomerFor(User $user): Customer
    {
        return Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Membuat kendaraan milik customer.
     */
    private function createVehicleFor(
        Customer $customer,
        string $licensePlate = 'B 1234 XYZ'
    ): Vehicle {
        return Vehicle::create([
            'license_plate' => $licensePlate,
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $customer->id,
        ]);
    }

    /**
     * Membuat booking dengan data default.
     */
    private function createBookingFor(
        Customer $customer,
        Vehicle $vehicle,
        string $status = 'PENDING',
        ?string $bookingCode = null
    ): Booking {
        return Booking::create([
            'booking_code' => $bookingCode ?? 'BK-' . str_pad(
                (string) random_int(1, 999999),
                6,
                '0',
                STR_PAD_LEFT
            ),
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00',
            'complaint' => 'Mesin terasa kasar',
            'status' => $status,
        ]);
    }

    public function test_booking_factory_creates_valid_customer_and_vehicle_relationship(): void
    {
        $booking = Booking::factory()->create();

        $this->assertNotNull($booking->customer_id);
        $this->assertNotNull($booking->vehicle_id);

        $this->assertEquals(
            $booking->customer_id,
            $booking->vehicle->customer_id
        );
    }

    public function test_customer_can_view_their_bookings(): void
    {
        $user = $this->createActiveUser(5);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = $this->createVehicleFor($customer);

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
        $user = $this->createActiveUser(5);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->createVehicleFor($customer);

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
        $user = $this->createActiveUser(5);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = $this->createVehicleFor($customer);

        $bookingDate = now()->addDays(3)->toDateString();

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.store'), [
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => $bookingDate,
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli dan cek rem',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Service Berkala',
            'booking_date' => $bookingDate,
            'booking_time' => '09:00:00',
            'complaint' => 'Ganti oli dan cek rem',
            'status' => 'PENDING',
        ]);

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_customer_cannot_create_booking_for_another_customers_vehicle(): void
    {
        $user = $this->createActiveUser(5);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherCustomer = Customer::factory()->create();

        $otherVehicle = $this->createVehicleFor(
            $otherCustomer,
            'B 5678 ABC'
        );

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.store'), [
                'vehicle_id' => $otherVehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => now()->addDays(3)->toDateString(),
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
            ]);

        $response->assertNotFound();

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_requires_required_fields(): void
    {
        $user = $this->createActiveUser(5);

        Customer::factory()->create([
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
        $user = $this->createActiveUser(5);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $vehicle = $this->createVehicleFor($customer);

        $bookingDate = now()->addDays(3)->toDateString();

        $response = $this
            ->actingAs($user)
            ->post(route('customer.bookings.store'), [
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => $bookingDate,
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
            ]);

        $response->assertRedirect();

        $booking = $customer->bookings()->first();

        $this->assertNotNull($booking);
        $this->assertNotEmpty($booking->booking_code);

        $this->assertMatchesRegularExpression(
            '/^BK-[A-Z0-9]{6}$/',
            $booking->booking_code
        );
    }

    public function test_customer_can_only_view_their_own_bookings(): void
    {
        $user = $this->createActiveUser(5);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherCustomer = Customer::factory()->create();

        $vehicle = $this->createVehicleFor($customer);

        $otherVehicle = $this->createVehicleFor(
            $otherCustomer,
            'B 5678 ABC'
        );

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
        $user = $this->createActiveUser(1);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.bookings.index'));

        $response->assertForbidden();
    }

    public function test_customer_can_cancel_pending_booking(): void
    {
        $user = $this->createCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $booking = $this->createBookingFor(
            $customer,
            $vehicle,
            'PENDING',
            'BK-123456'
        );

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
        $user = $this->createCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $booking = $this->createBookingFor(
            $customer,
            $vehicle,
            'PENDING',
            'BK-123456'
        );

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
        $user = $this->createCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $booking = $this->createBookingFor(
            $customer,
            $vehicle,
            'CONFIRMED',
            'BK-123456'
        );

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
        $user = $this->createCustomerUser();

        $otherUser = $this->createCustomerUser();

        $this->createCustomerFor($user);

        $otherCustomer = $this->createCustomerFor($otherUser);

        $otherVehicle = $this->createVehicleFor(
            $otherCustomer,
            'B 5678 ABC'
        );

        $booking = $this->createBookingFor(
            $otherCustomer,
            $otherVehicle,
            'PENDING',
            'BK-654321'
        );

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
        $user = $this->createCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $booking = $this->createBookingFor(
            $customer,
            $vehicle,
            'CANCELLED',
            'BK-123456'
        );

        $booking->update([
            'cancellation_reason' => 'Ada keperluan mendadak.',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.bookings.index'));

        $response->assertOk();
        $response->assertDontSee('BK-123456');
    }

    public function test_customer_can_review_completed_booking(): void
    {
        $user = $this->createCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $booking = $this->createBookingFor(
            $customer,
            $vehicle,
            'COMPLETED',
            'BK-123456'
        );

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
        $user = $this->createCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $booking = $this->createBookingFor(
            $customer,
            $vehicle,
            'PENDING',
            'BK-123456'
        );

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
        $user = $this->createCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $booking = $this->createBookingFor(
            $customer,
            $vehicle,
            'COMPLETED',
            'BK-123456'
        );

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
        $user = $this->createCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $booking = $this->createBookingFor(
            $customer,
            $vehicle,
            'COMPLETED',
            'BK-123456'
        );

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
        $user = $this->createCustomerUser();

        $otherUser = $this->createCustomerUser();

        $this->createCustomerFor($user);

        $otherCustomer = $this->createCustomerFor($otherUser);

        $otherVehicle = $this->createVehicleFor(
            $otherCustomer,
            'B 5678 ABC'
        );

        $booking = $this->createBookingFor(
            $otherCustomer,
            $otherVehicle,
            'COMPLETED',
            'BK-654321'
        );

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
