<?php

namespace Tests\Feature\Owner;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $ownerRoleId = $this->createRole('Owner');

        $this->owner = $this->createUser(
            $ownerRoleId,
            'owner'
        );
    }

   
    // Helper Methods
    private function createRole(string $name): int
    {
        return DB::table('roles')->insertGetId([
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createUser(
        int $roleId,
        string $identifier
    ): User {
        $user = new User();

        $user->forceFill([
            'name' => ucfirst($identifier),
            'email' => $identifier . '@example.test',
            'phone' => '08' . random_int(100000000, 999999999),
            'password' => Hash::make('password123'),
            'role_id' => $roleId,
            'is_active' => true,
        ]);

        $user->save();

        return $user;
    }

    private function createCustomer(): int
    {
        return DB::table('customers')->insertGetId([
            'user_id' => null,
            'name' => 'Test Customer ' . uniqid(),
            'email' => null,
            'phone' => '08' . random_int(100000000, 999999999),
            'address' => 'Test Address',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createVehicle(int $customerId): int
    {
        return DB::table('vehicles')->insertGetId([
            'customer_id' => $customerId,
            'license_plate' => 'B ' . random_int(1000, 9999) . ' '
                . strtoupper(substr(uniqid(), -3)),
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => 2024,
            'color' => 'Black',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createBooking(
        string $bookingCode,
        ?string $createdAt = null
    ): int {
        $customerId = $this->createCustomer();

        $vehicleId = $this->createVehicle($customerId);

        return DB::table('bookings')->insertGetId([
            'booking_code' => $bookingCode,
            'customer_id' => $customerId,
            'vehicle_id' => $vehicleId,
            'service_type' => 'Servis',
            'booking_date' => now()->toDateString(),
            'booking_time' => '09:00:00',
            'complaint' => 'Test complaint',
            'status' => 'PENDING',
            'cancellation_reason' => null,
            'created_at' => $createdAt ?? now(),
            'updated_at' => now(),
        ]);
    }


    // Index Tests
    public function test_owner_can_open_booking_page(): void
    {
        $this->actingAs($this->owner);

        $response = $this->get(route('owner.bookings.index'));

        $response->assertOk();

        $response->assertViewIs('owner.bookings.index');

        $response->assertViewHas('bookings');
    }

    public function test_booking_page_displays_booking_with_customer_and_vehicle(): void
    {
        $this->createBooking('BK-100001');

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.bookings.index'));

        $response->assertOk();

        $response->assertViewHas('bookings', function ($bookings) {
            $booking = $bookings->getCollection()->first();

            return $booking !== null
                && $booking->customer !== null
                && $booking->vehicle !== null
                && $booking->booking_code === 'BK-100001';
        });
    }

    public function test_bookings_are_ordered_from_newest_to_oldest(): void
    {
        $this->createBooking(
            'BK-100001',
            now()->subDays(2)->toDateTimeString()
        );

        $this->createBooking(
            'BK-100002',
            now()->subDay()->toDateTimeString()
        );

        $this->createBooking(
            'BK-100003',
            now()->toDateTimeString()
        );

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.bookings.index'));

        $response->assertOk();

        $response->assertViewHas('bookings', function ($bookings) {
            $bookingCodes = $bookings->getCollection()
                ->pluck('booking_code')
                ->values()
                ->all();

            return $bookingCodes === [
                'BK-100003',
                'BK-100002',
                'BK-100001',
            ];
        });
    }

    public function test_booking_page_displays_ten_bookings_per_page(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            $this->createBooking(
                'BK-' . str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                now()->subMinutes($i)->toDateTimeString()
            );
        }

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.bookings.index'));

        $response->assertOk();

        $response->assertViewHas('bookings', function ($bookings) {
            return $bookings->count() === 10
                && $bookings->total() === 12
                && $bookings->perPage() === 10
                && $bookings->currentPage() === 1;
        });
    }

    public function test_owner_can_view_second_page_of_bookings(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            $this->createBooking(
                'BK-' . str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                now()->subMinutes($i)->toDateTimeString()
            );
        }

        $this->actingAs($this->owner);

        $response = $this->get(
            route('owner.bookings.index', ['page' => 2])
        );

        $response->assertOk();

        $response->assertViewHas('bookings', function ($bookings) {
            return $bookings->count() === 2
                && $bookings->total() === 12
                && $bookings->currentPage() === 2;
        });
    }


    // Authorization Tests
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('owner.bookings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_owner_cannot_access_booking_page(): void
    {
        $mechanicRoleId = $this->createRole('Mechanic');

        $mechanic = $this->createUser(
            $mechanicRoleId,
            'mechanic'
        );

        $this->actingAs($mechanic);

        $response = $this->get(route('owner.bookings.index'));

        $response->assertForbidden();
    }
}
