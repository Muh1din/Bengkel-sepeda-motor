<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardTest extends TestCase
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

        // Pastikan user lolos middleware EnsureUserIsActive.
        // forceFill digunakan agar tidak bergantung pada $fillable.
        $user->forceFill([
            'is_active' => true,
        ])->save();

        return $user;
    }

    /**
     * Membuat customer yang terhubung dengan user.
     */
    private function createCustomerFor(User $user): Customer
    {
        return Customer::factory()->create([
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
        return $customer->vehicles()->create([
            'license_plate' => $licensePlate,
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Black',
        ]);
    }

    public function test_customer_can_access_customer_dashboard(): void
    {
        $user = $this->createActiveCustomerUser();

        $this->createCustomerFor($user);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.dashboard'));

        $response->assertOk();
    }

    public function test_customer_dashboard_displays_total_vehicles(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = $this->createCustomerFor($user);

        $customer->vehicles()->createMany([
            [
                'license_plate' => 'B 1234 XYZ',
                'brand' => 'Honda',
                'model' => 'Vario 160',
                'year' => '2024',
                'color' => 'Black',
            ],
            [
                'license_plate' => 'B 5678 ABC',
                'brand' => 'Yamaha',
                'model' => 'NMAX',
                'year' => '2023',
                'color' => 'Blue',
            ],
            [
                'license_plate' => 'B 9012 DEF',
                'brand' => 'Suzuki',
                'model' => 'NEX II',
                'year' => '2022',
                'color' => 'Red',
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.dashboard'));

        $response->assertOk();

        $response->assertViewHas('totalVehicles', 3);
    }

    public function test_customer_dashboard_displays_total_active_bookings(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = $this->createCustomerFor($user);

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
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-22',
                'booking_time' => '10:00:00',
                'complaint' => 'Cek rem',
                'status' => 'CONFIRMED',
            ],
            [
                'booking_code' => 'BK-000003',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-23',
                'booking_time' => '11:00:00',
                'complaint' => 'Mesin terasa kasar',
                'status' => 'IN_PROGRESS',
            ],
            [
                'booking_code' => 'BK-000004',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-24',
                'booking_time' => '12:00:00',
                'complaint' => 'Ganti kampas rem',
                'status' => 'COMPLETED',
            ],
            [
                'booking_code' => 'BK-000005',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-25',
                'booking_time' => '13:00:00',
                'complaint' => 'Cek motor',
                'status' => 'REJECTED',
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.dashboard'));

        $response->assertOk();

        $response->assertViewHas('activeBookings', 3);
    }

    public function test_customer_dashboard_displays_running_services(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $customer->bookings()->createMany([
            [
                'booking_code' => 'BK-000001',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-21',
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
                'status' => 'IN_PROGRESS',
            ],
            [
                'booking_code' => 'BK-000002',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-22',
                'booking_time' => '10:00:00',
                'complaint' => 'Cek rem',
                'status' => 'IN_PROGRESS',
            ],
            [
                'booking_code' => 'BK-000003',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-23',
                'booking_time' => '11:00:00',
                'complaint' => 'Ganti kampas rem',
                'status' => 'COMPLETED',
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.dashboard'));

        $response->assertOk();

        $response->assertViewHas('runningServices', 2);
    }

    public function test_customer_dashboard_displays_service_history(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        $customer->bookings()->createMany([
            [
                'booking_code' => 'BK-000001',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-21',
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
                'status' => 'COMPLETED',
            ],
            [
                'booking_code' => 'BK-000002',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-22',
                'booking_time' => '10:00:00',
                'complaint' => 'Cek rem',
                'status' => 'COMPLETED',
            ],
            [
                'booking_code' => 'BK-000003',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-23',
                'booking_time' => '11:00:00',
                'complaint' => 'Mesin kasar',
                'status' => 'IN_PROGRESS',
            ],
            [
                'booking_code' => 'BK-000004',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-24',
                'booking_time' => '12:00:00',
                'complaint' => 'Cek motor',
                'status' => 'REJECTED',
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customer.dashboard'));

        $response->assertOk();

        $response->assertViewHas('serviceHistory', 2);
    }

    public function test_customer_dashboard_displays_latest_bookings(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        try {
            Carbon::setTestNow('2026-09-20 08:00:00');

            $customer->bookings()->create([
                'booking_code' => 'BK-000001',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-21',
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
                'status' => 'COMPLETED',
            ]);

            Carbon::setTestNow('2026-09-20 09:00:00');

            $customer->bookings()->create([
                'booking_code' => 'BK-000002',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-22',
                'booking_time' => '10:00:00',
                'complaint' => 'Cek rem',
                'status' => 'COMPLETED',
            ]);

            Carbon::setTestNow('2026-09-20 10:00:00');

            $customer->bookings()->create([
                'booking_code' => 'BK-000003',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-23',
                'booking_time' => '11:00:00',
                'complaint' => 'Cek mesin',
                'status' => 'CONFIRMED',
            ]);

            Carbon::setTestNow('2026-09-20 11:00:00');

            $customer->bookings()->create([
                'booking_code' => 'BK-000004',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-24',
                'booking_time' => '12:00:00',
                'complaint' => 'Ganti kampas rem',
                'status' => 'PENDING',
            ]);

            Carbon::setTestNow('2026-09-20 12:00:00');

            $customer->bookings()->create([
                'booking_code' => 'BK-000005',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-25',
                'booking_time' => '13:00:00',
                'complaint' => 'Service CVT',
                'status' => 'IN_PROGRESS',
            ]);
        } finally {
            Carbon::setTestNow();
        }

        $response = $this
            ->actingAs($user)
            ->get(route('customer.dashboard'));

        $response->assertOk();

        $response->assertViewHas('latestBookings', function ($latestBookings) {
            return $latestBookings->count() === 3
                && $latestBookings->pluck('booking_code')->all() === [
                    'BK-000005',
                    'BK-000004',
                    'BK-000003',
                ];
        });
    }

    public function test_customer_dashboard_displays_current_service(): void
    {
        $user = $this->createActiveCustomerUser();

        $customer = $this->createCustomerFor($user);

        $vehicle = $this->createVehicleFor($customer);

        try {
            Carbon::setTestNow('2026-09-20 10:00:00');

            $customer->bookings()->create([
                'booking_code' => 'BK-000001',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-21',
                'booking_time' => '09:00:00',
                'complaint' => 'Ganti oli',
                'status' => 'IN_PROGRESS',
            ]);

            Carbon::setTestNow('2026-09-20 11:00:00');

            $latestBooking = $customer->bookings()->create([
                'booking_code' => 'BK-000002',
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Service Berkala',
                'booking_date' => '2026-09-22',
                'booking_time' => '10:00:00',
                'complaint' => 'Cek rem',
                'status' => 'IN_PROGRESS',
            ]);
        } finally {
            Carbon::setTestNow();
        }

        $response = $this
            ->actingAs($user)
            ->get(route('customer.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'currentService',
            function ($currentService) use ($latestBooking) {
                return $currentService !== null
                    && $currentService->id === $latestBooking->id
                    && $currentService->booking_code === 'BK-000002'
                    && $currentService->relationLoaded('vehicle')
                    && $currentService->vehicle->id === $latestBooking->vehicle_id;
            }
        );
    }
}
