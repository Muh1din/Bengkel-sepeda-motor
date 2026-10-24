<?php

namespace Tests\Feature\Mechanic;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkItem;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MechanicWorkOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $mechanic;

    private User $otherMechanic;

    private Customer $customer;

    private Vehicle $vehicle;

    private static int $bookingSequence = 0;

    private static int $workOrderSequence = 0;

    protected function setUp(): void
    {
        parent::setUp();

        self::$bookingSequence = 0;
        self::$workOrderSequence = 0;

        $roleId = DB::table('roles')->insertGetId([
            'name' => 'Mechanic',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->mechanic = $this->createMechanic(
            $roleId,
            'mechanic@test.com',
            '081234567801'
        );

        $this->otherMechanic = $this->createMechanic(
            $roleId,
            'other-mechanic@test.com',
            '081234567802'
        );

        $this->customer = Customer::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'phone' => '081234567803',
            'address' => 'Depok',
            'user_id' => null,
        ]);

        $this->vehicle = Vehicle::create([
            'license_plate' => 'B 1234 TST',
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Black',
            'customer_id' => $this->customer->id,
        ]);
    }

    //  Helper Methods
    private function createMechanic(
        int $roleId,
        string $email,
        string $phone
    ): User {
        $mechanic = User::create([
            'name' => 'Mechanic Test',
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make('password'),
            'role_id' => $roleId,
        ]);

        $mechanic->forceFill([
            'is_active' => true,
        ])->save();

        return $mechanic;
    }

    private function createBooking(): Booking
    {
        self::$bookingSequence++;

        return Booking::create([
            'booking_code' => sprintf(
                'BK-%06d',
                self::$bookingSequence
            ),
            'customer_id' => $this->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'service_type' => 'Servis',
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00:00',
            'complaint' => 'Pengujian work order mekanik',
            'status' => 'IN_PROGRESS',
        ]);
    }

    private function createWorkOrder(
        ?User $mechanic = null,
        string $status = 'IN_PROGRESS',
        ?string $completedAt = null
    ): WorkOrder {
        $mechanic ??= $this->mechanic;

        self::$workOrderSequence++;

        return WorkOrder::create([
            'booking_id' => $this->createBooking()->id,
            'mechanic_id' => $mechanic->id,
            'work_order_code' => sprintf(
                'WO-%06d',
                self::$workOrderSequence
            ),
            'received_at' => now(),
            'status' => $status,
            'notes' => 'Pengujian work order',
            'completed_at' => $completedAt,
        ]);
    }

    private function createWorkItem(
        WorkOrder $workOrder,
        string $status = 'PENDING'
    ): WorkItem {
        return WorkItem::create([
            'work_order_id' => $workOrder->id,
            'name' => 'Penggantian kampas rem',
            'type' => 'Servis',
            'description' => 'Pekerjaan untuk pengujian',
            'status' => $status,
        ]);
    }

    //  Index
    public function test_mechanic_can_open_work_order_list(): void
    {
        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.work-orders.index'));

        $response->assertOk();

        $response->assertViewIs('mechanic.work-orders.index');
    }

    public function test_work_order_list_only_contains_logged_in_mechanics_work_orders(): void
    {
        $ownWorkOrder = $this->createWorkOrder($this->mechanic);

        $this->createWorkOrder($this->otherMechanic);

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.work-orders.index'));

        $response->assertOk();

        $response->assertViewHas(
            'workOrders',
            function ($workOrders) use ($ownWorkOrder) {
                return $workOrders->count() === 1
                    && $workOrders->first()->id === $ownWorkOrder->id;
            }
        );
    }

   
    //  Show
    public function test_mechanic_can_open_own_work_order_detail(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.work-orders.show', $workOrder));

        $response->assertOk();

        $response->assertViewIs('mechanic.work-orders.show');

        $response->assertViewHas(
            'workOrder',
            fn($result) => $result->id === $workOrder->id
        );
    }

    public function test_mechanic_cannot_open_another_mechanics_work_order(): void
    {
        $workOrder = $this->createWorkOrder($this->otherMechanic);

        $response = $this->actingAs($this->mechanic)
            ->get(route('mechanic.work-orders.show', $workOrder));

        $response->assertForbidden();
    }


    //  Start Work Item
    public function test_mechanic_can_start_pending_work_item(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $workItem = $this->createWorkItem($workOrder, 'PENDING');

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.work-items.start', $workItem));

        $response->assertSessionHas(
            'success',
            'Pekerjaan berhasil dimulai.'
        );

        $this->assertDatabaseHas('work_items', [
            'id' => $workItem->id,
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function test_mechanic_cannot_start_work_item_that_is_already_in_progress(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $workItem = $this->createWorkItem(
            $workOrder,
            'IN_PROGRESS'
        );

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.work-items.start', $workItem));

        $response->assertSessionHas(
            'error',
            'Pekerjaan tidak dapat dimulai.'
        );

        $this->assertDatabaseHas('work_items', [
            'id' => $workItem->id,
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function test_mechanic_cannot_start_another_mechanics_work_item(): void
    {
        $workOrder = $this->createWorkOrder($this->otherMechanic);

        $workItem = $this->createWorkItem($workOrder);

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.work-items.start', $workItem));

        $response->assertForbidden();

        $this->assertDatabaseHas('work_items', [
            'id' => $workItem->id,
            'status' => 'PENDING',
        ]);
    }

    //  Complete Work Item
    public function test_mechanic_can_complete_work_item_in_progress(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $workItem = $this->createWorkItem(
            $workOrder,
            'IN_PROGRESS'
        );

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.work-items.complete', $workItem));

        $response->assertSessionHas(
            'success',
            'Pekerjaan berhasil diselesaikan.'
        );

        $this->assertDatabaseHas('work_items', [
            'id' => $workItem->id,
            'status' => 'COMPLETED',
        ]);
    }

    public function test_mechanic_cannot_complete_pending_work_item(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $workItem = $this->createWorkItem(
            $workOrder,
            'PENDING'
        );

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.work-items.complete', $workItem));

        $response->assertSessionHas(
            'error',
            'Pekerjaan belum sedang dikerjakan.'
        );

        $this->assertDatabaseHas('work_items', [
            'id' => $workItem->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_mechanic_cannot_complete_another_mechanics_work_item(): void
    {
        $workOrder = $this->createWorkOrder($this->otherMechanic);

        $workItem = $this->createWorkItem(
            $workOrder,
            'IN_PROGRESS'
        );

        $response = $this->actingAs($this->mechanic)
            ->patch(route('mechanic.work-items.complete', $workItem));

        $response->assertForbidden();

        $this->assertDatabaseHas('work_items', [
            'id' => $workItem->id,
            'status' => 'IN_PROGRESS',
        ]);
    }

    //  Work Order Completion Logic
    public function test_work_order_remains_in_progress_while_other_work_items_are_unfinished(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $completedItem = $this->createWorkItem(
            $workOrder,
            'IN_PROGRESS'
        );

        $this->createWorkItem(
            $workOrder,
            'PENDING'
        );

        $response = $this->actingAs($this->mechanic)
            ->patch(
                route('mechanic.work-items.complete', $completedItem)
            );

        $response->assertSessionHas(
            'success',
            'Pekerjaan berhasil diselesaikan.'
        );

        $this->assertDatabaseHas('work_items', [
            'id' => $completedItem->id,
            'status' => 'COMPLETED',
        ]);

        $this->assertDatabaseHas('work_orders', [
            'id' => $workOrder->id,
            'status' => 'IN_PROGRESS',
            'completed_at' => null,
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $workOrder->booking_id,
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function test_work_order_and_booking_are_completed_when_all_work_items_are_completed(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $firstItem = $this->createWorkItem(
            $workOrder,
            'COMPLETED'
        );

        $lastItem = $this->createWorkItem(
            $workOrder,
            'IN_PROGRESS'
        );

        $response = $this->actingAs($this->mechanic)
            ->patch(
                route('mechanic.work-items.complete', $lastItem)
            );

        $response->assertSessionHas(
            'success',
            'Pekerjaan berhasil diselesaikan.'
        );

        $this->assertDatabaseHas('work_items', [
            'id' => $firstItem->id,
            'status' => 'COMPLETED',
        ]);

        $this->assertDatabaseHas('work_items', [
            'id' => $lastItem->id,
            'status' => 'COMPLETED',
        ]);

        $this->assertDatabaseHas('work_orders', [
            'id' => $workOrder->id,
            'status' => 'COMPLETED',
        ]);

        $this->assertNotNull(
            $workOrder->fresh()->completed_at
        );

        $this->assertDatabaseHas('bookings', [
            'id' => $workOrder->booking_id,
            'status' => 'COMPLETED',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_open_work_order_list(): void
    {
        $response = $this->get(
            route('mechanic.work-orders.index')
        );

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_open_work_order_detail(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $response = $this->get(
            route('mechanic.work-orders.show', $workOrder)
        );

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_start_work_item(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $workItem = $this->createWorkItem($workOrder);

        $response = $this->patch(
            route('mechanic.work-items.start', $workItem)
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('work_items', [
            'id' => $workItem->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_guest_cannot_complete_work_item(): void
    {
        $workOrder = $this->createWorkOrder($this->mechanic);

        $workItem = $this->createWorkItem(
            $workOrder,
            'IN_PROGRESS'
        );

        $response = $this->patch(
            route('mechanic.work-items.complete', $workItem)
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('work_items', [
            'id' => $workItem->id,
            'status' => 'IN_PROGRESS',
        ]);
    }
}
