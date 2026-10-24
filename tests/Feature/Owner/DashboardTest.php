<?php

namespace Tests\Feature\Owner;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $mechanic;

    private int $bookingSequence = 0;
    private int $workOrderSequence = 0;
    private int $customerSequence = 0;
    private int $vehicleSequence = 0;
    private int $materialSequence = 0;

    protected function setUp(): void
    {
        parent::setUp();

        // Membuat waktu pengujian konsisten.
        Carbon::setTestNow('2026-10-19 12:00:00');

        $ownerRoleId = $this->createRole('Owner');
        $mechanicRoleId = $this->createRole('Mechanic');

        $this->owner = $this->createUser(
            $ownerRoleId,
            'owner'
        );

        $this->mechanic = $this->createUser(
            $mechanicRoleId,
            'mechanic'
        );
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

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
        $this->customerSequence++;

        return DB::table('customers')->insertGetId([
            'user_id' => null,
            'name' => 'Test Customer ' . $this->customerSequence,
            'email' => null,
            'phone' => '08' . str_pad(
                (string) $this->customerSequence,
                9,
                '0',
                STR_PAD_LEFT
            ),
            'address' => 'Test Address',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createVehicle(int $customerId): int
    {
        $this->vehicleSequence++;

        return DB::table('vehicles')->insertGetId([
            'customer_id' => $customerId,
            'license_plate' => 'B ' . $this->vehicleSequence . ' XYZ',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => 2024,
            'color' => 'Black',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createBooking(
        string $status = 'PENDING',
        ?Carbon $createdAt = null
    ): int {
        $this->bookingSequence++;

        $customerId = $this->createCustomer();
        $vehicleId = $this->createVehicle($customerId);
        $createdAt ??= now();

        return DB::table('bookings')->insertGetId([
            'booking_code' => 'BK-' . str_pad(
                (string) $this->bookingSequence,
                6,
                '0',
                STR_PAD_LEFT
            ),
            'customer_id' => $customerId,
            'vehicle_id' => $vehicleId,
            'service_type' => 'Servis',
            'booking_date' => $createdAt->toDateString(),
            'booking_time' => '09:00:00',
            'complaint' => 'Test complaint',
            'status' => $status,
            'cancellation_reason' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    private function createWorkOrder(
        ?Carbon $receivedAt = null,
        ?Carbon $completedAt = null
    ): int {
        $this->workOrderSequence++;

        $bookingId = $this->createBooking(
            'IN_PROGRESS'
        );

        $receivedAt ??= now();

        return DB::table('work_orders')->insertGetId([
            'booking_id' => $bookingId,
            'mechanic_id' => $this->mechanic->id,
            'work_order_code' => 'WO-' . str_pad(
                (string) $this->workOrderSequence,
                6,
                '0',
                STR_PAD_LEFT
            ),
            'received_at' => $receivedAt,
            'status' => $completedAt ? 'COMPLETED' : 'IN_PROGRESS',
            'notes' => null,
            'completed_at' => $completedAt,
            'created_at' => $receivedAt,
            'updated_at' => $completedAt ?? $receivedAt,
        ]);
    }

    private function createMaterialCategory(): int
    {
        return DB::table('material_categories')->insertGetId([
            'name' => 'Test Category',
            'description' => 'Category for dashboard testing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createMaterial(
        int $categoryId,
        int $stock,
        int $minimumStock
    ): int {
        $this->materialSequence++;

        return DB::table('materials')->insertGetId([
            'material_category_id' => $categoryId,
            'name' => 'Test Material ' . $this->materialSequence,
            'barcode' => 'TEST-' . str_pad(
                (string) $this->materialSequence,
                6,
                '0',
                STR_PAD_LEFT
            ),
            'unit' => 'pcs',
            'stock' => $stock,
            'minimum_stock' => $minimumStock,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Access Tests
    |--------------------------------------------------------------------------
    */

    public function test_owner_can_open_dashboard(): void
    {
        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewIs('owner.dashboard');

        $response->assertViewHas('statistics');
        $response->assertViewHas('recentWorkOrders');
        $response->assertViewHas('chartLabels');
        $response->assertViewHas('bookingChartData');
        $response->assertViewHas('completedChartData');
        $response->assertViewHas('bookingStatusData');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('owner.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_owner_cannot_access_dashboard(): void
    {
        $this->actingAs($this->mechanic);

        $response = $this->get(route('owner.dashboard'));

        $response->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics Tests
    |--------------------------------------------------------------------------
    */

    public function test_dashboard_displays_correct_booking_statistics(): void
    {
        $this->createBooking('PENDING');
        $this->createBooking('PENDING');
        $this->createBooking('CONFIRMED');
        $this->createBooking('COMPLETED');

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'statistics',
            function (array $statistics) {
                return $statistics['total_bookings'] === 4
                    && $statistics['pending_bookings'] === 2;
            }
        );
    }

    public function test_dashboard_displays_correct_work_order_statistics(): void
    {
        // Dua work order aktif.
        $this->createWorkOrder(
            Carbon::today()->subDays(2),
            null
        );

        $this->createWorkOrder(
            Carbon::today()->subDay(),
            null
        );

        // Satu work order selesai.
        $this->createWorkOrder(
            Carbon::today()->subDays(3),
            Carbon::today()->subDays(2)
        );

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'statistics',
            function (array $statistics) {
                return $statistics['active_work_orders'] === 2
                    && $statistics['completed_work_orders'] === 1;
            }
        );
    }

    public function test_dashboard_displays_correct_material_stock_statistics(): void
    {
        $categoryId = $this->createMaterialCategory();

        // Stok aman: 10 > 5.
        $this->createMaterial($categoryId, 10, 5);

        // Stok rendah: 3 <= 5.
        $this->createMaterial($categoryId, 3, 5);

        // Stok sama dengan minimum: 5 <= 5.
        $this->createMaterial($categoryId, 5, 5);

        // Stok kosong.
        $this->createMaterial($categoryId, 0, 5);

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'statistics',
            function (array $statistics) {
                return $statistics['total_materials'] === 4
                    && $statistics['low_stock_materials'] === 3
                    && $statistics['out_of_stock_materials'] === 1;
            }
        );
    }

    public function test_dashboard_returns_zero_statistics_when_database_is_empty(): void
    {
        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'statistics',
            function (array $statistics) {
                return $statistics['total_bookings'] === 0
                    && $statistics['pending_bookings'] === 0
                    && $statistics['active_work_orders'] === 0
                    && $statistics['completed_work_orders'] === 0
                    && $statistics['total_materials'] === 0
                    && $statistics['low_stock_materials'] === 0
                    && $statistics['out_of_stock_materials'] === 0;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Recent Work Orders Tests
    |--------------------------------------------------------------------------
    */

    public function test_dashboard_displays_only_five_most_recent_work_orders(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->createWorkOrder(
                Carbon::today()->subDays(7 - $i)
            );
        }

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'recentWorkOrders',
            function ($workOrders) {
                return $workOrders->count() === 5
                    && $workOrders->first()->id
                    > $workOrders->last()->id;
            }
        );
    }

    public function test_recent_work_orders_include_vehicle_and_mechanic(): void
    {
        $this->createWorkOrder();

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'recentWorkOrders',
            function ($workOrders) {
                $workOrder = $workOrders->first();

                return $workOrder !== null
                    && $workOrder->booking !== null
                    && $workOrder->booking->vehicle !== null
                    && $workOrder->mechanic !== null;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Chart Tests
    |--------------------------------------------------------------------------
    */

    public function test_dashboard_chart_contains_seven_days(): void
    {
        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas('chartLabels', function (array $labels) {
            return count($labels) === 7;
        });

        $response->assertViewHas(
            'bookingChartData',
            function (array $data) {
                return count($data) === 7;
            }
        );

        $response->assertViewHas(
            'completedChartData',
            function (array $data) {
                return count($data) === 7;
            }
        );
    }

    public function test_booking_chart_counts_bookings_in_the_last_seven_days(): void
    {
        // Booking enam hari lalu, masih termasuk rentang.
        $this->createBooking(
            'PENDING',
            Carbon::today()->subDays(6)->setTime(10, 0)
        );

        // Booking hari ini.
        $this->createBooking(
            'PENDING',
            Carbon::today()->setTime(9, 0)
        );

        // Booking tujuh hari lalu, di luar rentang.
        $this->createBooking(
            'PENDING',
            Carbon::today()->subDays(7)->setTime(10, 0)
        );

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'bookingChartData',
            function (array $data) {
                return array_sum($data) === 2;
            }
        );
    }

    public function test_completed_work_order_chart_counts_only_completed_orders_in_range(): void
    {
        // Work order selesai hari ini.
        $this->createWorkOrder(
            Carbon::today()->subDay(),
            Carbon::today()->setTime(10, 0)
        );

        // Work order selesai enam hari lalu.
        $this->createWorkOrder(
            Carbon::today()->subDays(7),
            Carbon::today()->subDays(6)->setTime(10, 0)
        );

        // Work order selesai tujuh hari lalu, di luar rentang.
        $this->createWorkOrder(
            Carbon::today()->subDays(8),
            Carbon::today()->subDays(7)->setTime(10, 0)
        );

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'completedChartData',
            function (array $data) {
                return array_sum($data) === 2;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Status Distribution Tests
    |--------------------------------------------------------------------------
    */

    public function test_dashboard_displays_booking_status_distribution(): void
    {
        $this->createBooking('PENDING');
        $this->createBooking('PENDING');
        $this->createBooking('CONFIRMED');
        $this->createBooking('IN_PROGRESS');
        $this->createBooking('COMPLETED');

        $this->actingAs($this->owner);

        $response = $this->get(route('owner.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'bookingStatusData',
            function ($bookingStatusData) {
                return (int) $bookingStatusData['PENDING'] === 2
                    && (int) $bookingStatusData['CONFIRMED'] === 1
                    && (int) $bookingStatusData['IN_PROGRESS'] === 1
                    && (int) $bookingStatusData['COMPLETED'] === 1;
            }
        );
    }
}
