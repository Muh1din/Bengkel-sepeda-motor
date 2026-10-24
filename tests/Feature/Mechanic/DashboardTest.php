<?php

namespace Tests\Feature\Mechanic;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleInspection;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $mechanic;

    private Customer $customer;

    private Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role Mechanic secara manual.
        $roleId = DB::table('roles')->insertGetId([
            'name' => 'Mechanic',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat user mekanik.
        $this->mechanic = User::create([
            'name' => 'Mechanic Test',
            'email' => 'mechanic@test.com',
            'phone' => '081234567801',
            'password' => Hash::make('password'),
            'role_id' => $roleId,
        ]);

        // Aktifkan akun jika aplikasi menggunakan is_active.
        $this->mechanic->forceFill([
            'is_active' => true,
        ])->save();

        // Buat customer tanpa factory.
        $this->customer = Customer::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'phone' => '081234567802',
            'address' => 'Depok',
            'user_id' => null,
        ]);

        // Buat kendaraan.
        $this->vehicle = Vehicle::create([
            'license_plate' => 'B 1234 TST',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $this->customer->id,
        ]);
    }

    /**
     * Membuat booking untuk kebutuhan pengujian.
     */
    private function createBooking(): Booking
    {
        return Booking::create([
            'booking_code' => 'BK-' . fake()->unique()->numerify('######'),
            'customer_id' => $this->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'service_type' => 'Servis',
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00:00',
            'complaint' => 'Pengujian dashboard mekanik',
            'status' => 'IN_PROGRESS',
        ]);
    }

    /**
     * Membuat work order untuk mekanik tertentu.
     */
    private function createWorkOrder(
        User $mechanic,
        string $status = 'IN_PROGRESS',
        ?string $completedAt = null
    ): WorkOrder {
        return WorkOrder::create([
            'booking_id' => $this->createBooking()->id,
            'mechanic_id' => $mechanic->id,
            'work_order_code' => 'WO-' . fake()->unique()->numerify('######'),
            'received_at' => now(),
            'status' => $status,
            'notes' => 'Pengujian work order',
            'completed_at' => $completedAt,
        ]);
    }

    /**
     * Membuat inspeksi untuk mekanik tertentu.
     */
    private function createInspection(
        User $mechanic,
        string $status = 'IN_PROGRESS'
    ): VehicleInspection {
        return VehicleInspection::create([
            'booking_id' => $this->createBooking()->id,
            'mechanic_id' => $mechanic->id,
            'status' => $status,
            'inspection' => 'Pemeriksaan kendaraan',
            'diagnosis' => 'Kondisi kendaraan normal',
            'recommendation' => 'Lakukan servis berkala',
            'created_by' => $mechanic->id,
        ]);
    }

    /**
     * 1. Mekanik dapat membuka dashboard.
     */
    public function test_mechanic_can_open_dashboard(): void
    {
        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.dashboard'));

        $response->assertOk();

        $response->assertViewIs('mechanic.dashboard');
    }

    /**
     * 2. Dashboard menghitung inspeksi milik mekanik.
     */
    public function test_dashboard_counts_assigned_inspections(): void
    {
        $this->createInspection($this->mechanic);
        $this->createInspection($this->mechanic);
        $this->createInspection($this->mechanic, 'COMPLETED');

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.dashboard'));

        $response->assertOk();

        $response->assertViewHas('assignedInspections', 3);
        $response->assertViewHas('pendingInspections', 2);
    }

    /**
     * 3. Dashboard menghitung work order yang belum selesai.
     */
    public function test_dashboard_counts_uncompleted_work_orders(): void
    {
        $this->createWorkOrder($this->mechanic, 'IN_PROGRESS');
        $this->createWorkOrder($this->mechanic, 'IN_PROGRESS');
        $this->createWorkOrder($this->mechanic, 'COMPLETED', now()->toDateTimeString());

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.dashboard'));

        $response->assertOk();

        $response->assertViewHas('assignedWorkOrders', 2);
        $response->assertViewHas('inProgressWorkOrders', 2);
    }

    /**
     * 4. Dashboard menghitung work order yang selesai hari ini.
     */
    public function test_dashboard_counts_work_orders_completed_today(): void
    {
        $this->createWorkOrder(
            $this->mechanic,
            'COMPLETED',
            now()->toDateTimeString()
        );

        $this->createWorkOrder(
            $this->mechanic,
            'COMPLETED',
            now()->subDay()->toDateTimeString()
        );

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.dashboard'));

        $response->assertOk();

        $response->assertViewHas('completedToday', 1);
    }

    /**
     * 5. Dashboard hanya menampilkan maksimal lima work order terbaru.
     */
    public function test_dashboard_displays_only_five_recent_work_orders(): void
    {
        for ($i = 0; $i < 7; $i++) {
            $this->createWorkOrder($this->mechanic);
        }

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'recentWorkOrders',
            function ($workOrders) {
                return $workOrders->count() === 5;
            }
        );
    }

    /**
     * 6. Dashboard hanya menghitung data milik mekanik yang login.
     */
    public function test_dashboard_only_counts_work_orders_and_inspections_assigned_to_logged_in_mechanic(): void
    {
        // Buat role dan mekanik kedua.
        $roleId = DB::table('roles')
            ->where('name', 'Mechanic')
            ->value('id');

        $otherMechanic = User::create([
            'name' => 'Other Mechanic',
            'email' => 'other-mechanic@test.com',
            'phone' => '081234567803',
            'password' => Hash::make('password'),
            'role_id' => $roleId,
        ]);

        $otherMechanic->forceFill([
            'is_active' => true,
        ])->save();

        // Dua work order milik mekanik yang login.
        $this->createWorkOrder($this->mechanic);
        $this->createWorkOrder($this->mechanic);

        // Tiga work order milik mekanik lain.
        $this->createWorkOrder($otherMechanic);
        $this->createWorkOrder($otherMechanic);
        $this->createWorkOrder($otherMechanic);

        // Inspeksi milik masing-masing mekanik.
        $this->createInspection($this->mechanic);
        $this->createInspection($otherMechanic);
        $this->createInspection($otherMechanic);

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.dashboard'));

        $response->assertOk();

        $response->assertViewHas('assignedWorkOrders', 2);
        $response->assertViewHas('inProgressWorkOrders', 2);

        $response->assertViewHas('assignedInspections', 1);
        $response->assertViewHas('pendingInspections', 1);
    }

    /**
     * 7. User yang belum login tidak dapat membuka dashboard.
     */
    public function test_guest_cannot_open_mechanic_dashboard(): void
    {
        $response = $this->get(route('mechanic.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
