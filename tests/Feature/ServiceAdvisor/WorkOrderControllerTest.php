<?php

namespace Tests\Feature\ServiceAdvisor;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\RecommendedWork;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleInspection;
use App\Models\WorkItem;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $serviceAdvisor;

    private User $mechanic;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceAdvisor = User::whereHas('role', function ($query) {
            $query->where('name', 'ServiceAdvisor');
        })->firstOrFail();

        $this->mechanic = User::whereHas('role', function ($query) {
            $query->where('name', 'Mechanic');
        })->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_open_work_order_index(): void
    {
        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.work-orders.index'));

        $response->assertOk();

        $response->assertViewIs('service-advisor.work-orders.index');

        $response->assertViewHas('readyInspections');

        $response->assertViewHas('workOrders');
    }

    public function test_work_order_index_only_includes_completed_inspections(): void
    {
        $completedInspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $inProgressInspection = $this->createInspection([
            'status' => 'IN_PROGRESS',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.work-orders.index'));

        $response->assertOk();

        $response->assertViewHas('readyInspections', function ($inspections) use (
            $completedInspection,
            $inProgressInspection
        ) {
            $ids = $inspections->pluck('id');

            return $ids->contains($completedInspection->id)
                && !$ids->contains($inProgressInspection->id);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_create_work_order_from_completed_inspection(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->post(route(
                'service-advisor.work-orders.store',
                $inspection
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Work Order berhasil dibuat.'
        );

        $this->assertDatabaseHas('work_orders', [
            'booking_id' => $inspection->booking_id,
            'mechanic_id' => $inspection->mechanic_id,
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function test_work_order_cannot_be_created_when_inspection_is_not_completed(): void
    {
        $inspection = $this->createInspection([
            'status' => 'IN_PROGRESS',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->from(route('service-advisor.work-orders.index'))
            ->post(route(
                'service-advisor.work-orders.store',
                $inspection
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Inspeksi belum selesai.'
        );

        $this->assertDatabaseMissing('work_orders', [
            'booking_id' => $inspection->booking_id,
        ]);
    }

    public function test_work_order_cannot_be_created_while_recommended_work_is_pending(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $this->createRecommendedWork($inspection, [
            'approval_status' => 'PENDING',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->from(route('service-advisor.work-orders.index'))
            ->post(route(
                'service-advisor.work-orders.store',
                $inspection
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Masih ada pekerjaan yang belum memiliki keputusan pelanggan.'
        );

        $this->assertDatabaseMissing('work_orders', [
            'booking_id' => $inspection->booking_id,
        ]);
    }

    public function test_work_order_cannot_be_created_twice_for_the_same_booking(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $existingWorkOrder = $this->createWorkOrder($inspection);

        $response = $this->actingAs($this->serviceAdvisor)
            ->from(route('service-advisor.work-orders.index'))
            ->post(route(
                'service-advisor.work-orders.store',
                $inspection
            ));

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Work Order untuk booking ini sudah dibuat.'
        );

        $this->assertDatabaseCount('work_orders', 1);

        $this->assertDatabaseHas('work_orders', [
            'id' => $existingWorkOrder->id,
            'booking_id' => $inspection->booking_id,
        ]);
    }

    public function test_accepted_recommended_work_becomes_a_work_item(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $recommendedWork = $this->createRecommendedWork($inspection, [
            'name' => 'Penggantian kampas rem',
            'description' => 'Ganti kampas rem depan.',
            'approval_status' => 'ACCEPTED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->post(route(
                'service-advisor.work-orders.store',
                $inspection
            ));

        $response->assertRedirect();

        $workOrder = WorkOrder::where(
            'booking_id',
            $inspection->booking_id
        )->firstOrFail();

        $this->assertDatabaseHas('work_items', [
            'work_order_id' => $workOrder->id,
            'name' => $recommendedWork->name,
            'type' => 'RECOMMENDED',
            'description' => $recommendedWork->description,
            'status' => 'PENDING',
        ]);
    }

    public function test_rejected_recommended_work_does_not_become_a_work_item(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $recommendedWork = $this->createRecommendedWork($inspection, [
            'name' => 'Penggantian kampas rem',
            'approval_status' => 'REJECTED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->post(route(
                'service-advisor.work-orders.store',
                $inspection
            ));

        $response->assertRedirect();

        $workOrder = WorkOrder::where(
            'booking_id',
            $inspection->booking_id
        )->firstOrFail();

        $this->assertDatabaseMissing('work_items', [
            'work_order_id' => $workOrder->id,
            'name' => $recommendedWork->name,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MONITORING INDEX
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_open_monitoring_index(): void
    {
        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.monitoring.index'));

        $response->assertOk();

        $response->assertViewIs('service-advisor.monitoring.index');

        $response->assertViewHas('workOrders');
    }

    public function test_monitoring_index_includes_in_progress_work_orders(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $workOrder = $this->createWorkOrder($inspection, [
            'status' => 'IN_PROGRESS',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.monitoring.index'));

        $response->assertOk();

        $response->assertViewHas('workOrders', function ($workOrders) use (
            $workOrder
        ) {
            return $workOrders->contains('id', $workOrder->id);
        });
    }

    public function test_monitoring_index_excludes_completed_work_orders(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $workOrder = $this->createWorkOrder($inspection, [
            'status' => 'COMPLETED',
        ]);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route('service-advisor.monitoring.index'));

        $response->assertOk();

        $response->assertViewHas('workOrders', function ($workOrders) use (
            $workOrder
        ) {
            return !$workOrders->contains('id', $workOrder->id);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MONITORING DETAIL
    |--------------------------------------------------------------------------
    */

    public function test_service_advisor_can_open_work_order_monitoring_detail(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $workOrder = $this->createWorkOrder($inspection);

        $response = $this->actingAs($this->serviceAdvisor)
            ->get(route(
                'service-advisor.monitoring.show',
                $workOrder
            ));

        $response->assertOk();

        $response->assertViewIs('service-advisor.monitoring.show');

        $response->assertViewHas(
            'workOrder',
            fn($result) => $result->id === $workOrder->id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATION
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_open_work_order_index(): void
    {
        $response = $this->get(
            route('service-advisor.work-orders.index')
        );

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_create_work_order(): void
    {
        $inspection = $this->createInspection([
            'status' => 'COMPLETED',
        ]);

        $response = $this->post(route(
            'service-advisor.work-orders.store',
            $inspection
        ));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('work_orders', [
            'booking_id' => $inspection->booking_id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST DATA HELPERS
    |--------------------------------------------------------------------------
    */

    private function createInspection(
        array $attributes = []
    ): VehicleInspection {
        $customer = Customer::query()->firstOrFail();

        $vehicle = Vehicle::where(
            'customer_id',
            $customer->id
        )->firstOrFail();

        $booking = Booking::forceCreate([
            'booking_code' => 'BK-' . fake()->unique()->numerify('######'),
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'booking_time' => '10:00:00',
            'service_type' => 'Servis',
            'complaint' => 'Pemeriksaan kondisi kendaraan.',
            'status' => 'CONFIRMED',
        ]);

        return VehicleInspection::forceCreate(array_merge([
            'booking_id' => $booking->id,
            'mechanic_id' => $this->mechanic->id,
            'status' => 'COMPLETED',
            'created_by' => $this->serviceAdvisor->id,
        ], $attributes));
    }

    private function createRecommendedWork(
        VehicleInspection $inspection,
        array $attributes = []
    ): RecommendedWork {
        return RecommendedWork::forceCreate(array_merge([
            'vehicle_inspection_id' => $inspection->id,
            'name' => 'Pemeriksaan tambahan',
            'description' => 'Pemeriksaan komponen kendaraan.',
            'estimated_cost' => 150000,
            'approval_status' => 'ACCEPTED',
        ], $attributes));
    }

    private function createWorkOrder(
        VehicleInspection $inspection,
        array $attributes = []
    ): WorkOrder {
        return WorkOrder::forceCreate(array_merge([
            'booking_id' => $inspection->booking_id,
            'mechanic_id' => $inspection->mechanic_id,
            'work_order_code' => 'WO-' . fake()->unique()->numerify('######'),
            'received_at' => now(),
            'status' => 'IN_PROGRESS',
        ], $attributes));
    }
}
