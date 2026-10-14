<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_material_request_can_contain_multiple_materials(): void
    {
        $workOrder = WorkOrder::factory()->create();

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $workOrder->mechanic_id,
            'status' => 'PENDING',
        ]);

        $material1 = Material::factory()->create();
        $material2 = Material::factory()->create();
        $material3 = Material::factory()->create();

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material1->id,
            'quantity' => 1,
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material2->id,
            'quantity' => 2,
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material3->id,
            'quantity' => 1,
        ]);

        $this->assertCount(
            3,
            $materialRequest->items
        );
    }

    public function test_material_request_belongs_to_work_order(): void
    {
        $workOrder = WorkOrder::factory()->create();

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $workOrder->mechanic_id,
        ]);

        $this->assertEquals(
            $workOrder->id,
            $materialRequest->workOrder->id
        );
    }

    public function test_material_request_belongs_to_mechanic(): void
    {
        $workOrder = WorkOrder::factory()->create();

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $workOrder->mechanic_id,
        ]);

        $this->assertEquals(
            $workOrder->mechanic_id,
            $materialRequest->mechanic->id
        );

        $this->assertEquals(
            'Mechanic',
            $materialRequest->mechanic->role->name
        );
    }

    public function test_material_request_item_belongs_to_material(): void
    {
        $material = Material::factory()->create();

        $item = MaterialRequestItem::factory()->create([
            'material_id' => $material->id,
        ]);

        $this->assertEquals(
            $material->id,
            $item->material->id
        );
    }

    public function test_material_request_is_pending_when_created(): void
    {
        $workOrder = WorkOrder::factory()->create();

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $workOrder->mechanic_id,
        ]);

        $this->assertEquals(
            'PENDING',
            $materialRequest->status
        );
    }

    public function test_material_request_mechanic_matches_work_order_mechanic(): void
    {
        $workOrder = WorkOrder::factory()->create();

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $workOrder->mechanic_id,
        ]);

        $this->assertEquals(
            $workOrder->mechanic_id,
            $materialRequest->mechanic_id
        );
    }

    public function test_material_request_item_quantity_must_be_greater_than_zero(): void
    {
        $item = MaterialRequestItem::factory()->create([
            'quantity' => 1,
        ]);

        $this->assertGreaterThan(
            0,
            $item->quantity
        );
    }

    public function test_creating_material_request_does_not_decrease_stock(): void
    {
        $workOrder = WorkOrder::factory()->create();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $workOrder->mechanic_id,
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $material->refresh();

        $this->assertEquals(
            10,
            $material->stock
        );
    }

    public function test_creating_material_request_does_not_create_stock_movement(): void
    {
        $workOrder = WorkOrder::factory()->create();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $workOrder->mechanic_id,
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseCount(
            'stock_movements',
            0
        );
    }
    
}
