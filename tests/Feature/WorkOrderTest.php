<?php

namespace Tests\Feature;

use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_work_order_factory_creates_valid_work_order(): void
    {
        $workOrder = WorkOrder::factory()->create();

        $this->assertNotNull($workOrder->booking_id);
        $this->assertNotNull($workOrder->mechanic_id);
        $this->assertNotEmpty($workOrder->work_order_code);

        $this->assertEquals(
            'Mechanic',
            $workOrder->mechanic->role->name
        );

        $this->assertEquals(
            $workOrder->booking->customer_id,
            $workOrder->booking->vehicle->customer_id
        );
    }
}
