<?php

namespace Tests\Feature\ServiceAdvisor;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $serviceAdvisor;

    protected User $mechanic;

    protected function setUp(): void
    {
        parent::setUp();

        // Mengambil akun Service Advisor dan Mechanic dari seeder.
        $this->serviceAdvisor = User::whereHas(
            'role',
            fn($query) => $query->where('name', 'ServiceAdvisor')
        )->firstOrFail();

        $this->mechanic = User::whereHas(
            'role',
            fn($query) => $query->where('name', 'Mechanic')
        )->firstOrFail();
    }

    /**
     * Membuat booking menggunakan customer dan kendaraan
     * yang sudah tersedia dari seeder.
     */
    private function createBooking(array $attributes = []): Booking
    {
        $customer = Customer::query()->firstOrFail();

        $vehicle = Vehicle::where('customer_id', $customer->id)
            ->firstOrFail();

        return Booking::create(array_merge([
            'booking_code' => 'BK-' . fake()->unique()->numerify('######'),
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'booking_time' => '10:00:00',
            'service_type' => 'Servis',
            'complaint' => 'Pemeriksaan kondisi kendaraan.',
            'status' => 'PENDING',
        ], $attributes));
    }

    /*
    |--------------------------------------------------------------------------
    | 1. INDEX
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_open_booking_page(): void
    {
        $this->createBooking();

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.bookings'));

        $response->assertOk();

        $response->assertViewIs('service-advisor.booking');

        $response->assertViewHas('bookings');

        $response->assertViewHas('mechanics');
    }

    /*
    |--------------------------------------------------------------------------
    | 2. CONFIRM
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_confirm_pending_booking(): void
    {
        $booking = $this->createBooking();

        // Memastikan pengaturan kapasitas tersedia.
        $this->setBookingCapacity(10);

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.confirm',
                $booking
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Booking berhasil dikonfirmasi.'
        );

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'CONFIRMED',
        ]);
    }

    public function test_booking_cannot_be_confirmed_when_capacity_is_full(): void
    {
        $booking = $this->createBooking();

        $this->setBookingCapacity(1);

        // Booking lain sudah menggunakan kapasitas yang tersedia.
        $this->createBooking([
            'status' => 'CONFIRMED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.confirm',
                $booking
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Kapasitas booking sudah penuh. Booking tidak dapat dikonfirmasi.'
        );

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_non_pending_booking_cannot_be_confirmed(): void
    {
        $booking = $this->createBooking([
            'status' => 'CONFIRMED',
        ]);

        $this->setBookingCapacity(10);

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.confirm',
                $booking
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Booking ini tidak dapat dikonfirmasi.'
        );

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'CONFIRMED',
        ]);
    }

    public function test_booking_cannot_be_confirmed_without_capacity_setting(): void
    {
        $booking = $this->createBooking();

        // Menghapus konfigurasi kapasitas.
        \Illuminate\Support\Facades\DB::table('settings')
            ->where('key', 'max_booking_slots')
            ->delete();

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.confirm',
                $booking
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Pengaturan maksimal booking belum tersedia.'
        );

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'PENDING',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. REJECT
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_reject_booking(): void
    {
        $booking = $this->createBooking();

        $reason = 'Jadwal servis penuh. Silakan memilih jadwal lain.';

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.reject',
                $booking
            ), [
                'rejection_reason' => $reason,
            ]);

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Booking berhasil ditolak.'
        );

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'REJECTED',
            'rejection_reason' => $reason,
        ]);
    }

    public function test_reject_booking_requires_rejection_reason(): void
    {
        $booking = $this->createBooking();

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.reject',
                $booking
            ), [
                'rejection_reason' => '',
            ]);

        $response->assertSessionHasErrors([
            'rejection_reason',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_reject_booking_validates_maximum_reason_length(): void
    {
        $booking = $this->createBooking();

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.reject',
                $booking
            ), [
                'rejection_reason' => str_repeat('A', 1001),
            ]);

        $response->assertSessionHasErrors([
            'rejection_reason',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'PENDING',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 4. RECEIVE
    |--------------------------------------------------------------------------
    */

    public function test_vehicle_cannot_be_received_when_booking_is_not_confirmed(): void
    {
        $booking = $this->createBooking([
            'status' => 'PENDING',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.receive',
                $booking
            ), [
                'mechanic_id' => $this->mechanic->id,
            ]);

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Kendaraan tidak dapat diterima.'
        );

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_receive_booking_requires_mechanic_id(): void
    {
        $booking = $this->createBooking([
            'status' => 'CONFIRMED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.receive',
                $booking
            ), []);

        $response->assertSessionHasErrors([
            'mechanic_id',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'CONFIRMED',
        ]);
    }

    public function test_receive_booking_rejects_nonexistent_mechanic(): void
    {
        $booking = $this->createBooking([
            'status' => 'CONFIRMED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->patch(route(
                'service-advisor.bookings.receive',
                $booking
            ), [
                'mechanic_id' => 999999,
            ]);

        $response->assertSessionHasErrors([
            'mechanic_id',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'CONFIRMED',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    private function setBookingCapacity(int $capacity): void
    {
        \Illuminate\Support\Facades\DB::table('settings')
            ->updateOrInsert(
                ['key' => 'max_booking_slots'],
                ['value' => (string) $capacity]
            );
    }
}
