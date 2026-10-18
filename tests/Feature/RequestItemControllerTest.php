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

class RequestItemControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createMechanic(): User
    {
        $mechanicRole = Role::firstOrCreate([
            'name' => 'Mechanic',
        ]);

        return User::factory()->create([
            'role_id' => $mechanicRole->id,
            'is_active' => true,
        ]);
    }

    public function test_mechanic_can_create_material_request_for_their_work_order(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 2,
                    ],
                ],
                'notes' => 'Material untuk servis mesin',
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('material_requests', [
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
            'notes' => 'Material untuk servis mesin',
        ]);

        $this->assertDatabaseHas('material_request_items', [
            'material_id' => $material->id,
            'quantity' => 2,
        ]);
    }

    public function test_mechanic_cannot_create_material_request_for_another_mechanics_work_order(): void
    {
        $mechanic = $this->createMechanic();

        $otherMechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $otherMechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 2,
                    ],
                ],
            ]
        );

        $response->assertForbidden();

        $this->assertDatabaseCount('material_requests', 0);
        $this->assertDatabaseCount('material_request_items', 0);
    }

    public function test_work_order_id_is_required(): void
    {
        $mechanic = $this->createMechanic();

        $material = Material::factory()->create();

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('work_order_id');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_work_order_must_exist(): void
    {
        $mechanic = $this->createMechanic();

        $material = Material::factory()->create();

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => 999999,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('work_order_id');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_items_are_required(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
            ]
        );

        $response->assertSessionHasErrors('items');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_items_must_contain_at_least_one_item(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [],
            ]
        );

        $response->assertSessionHasErrors('items');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_material_id_is_required(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('items.0.material_id');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_material_must_exist(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => 999999,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('items.0.material_id');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_quantity_is_required(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create();

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('items.0.quantity');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_quantity_must_be_integer(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create();

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 'abc',
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('items.0.quantity');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_quantity_must_be_greater_than_zero(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create();

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 0,
                    ],
                ],
            ]
        );

        $response->assertSessionHasErrors('items.0.quantity');

        $this->assertDatabaseCount('material_requests', 0);
    }

    public function test_notes_are_optional(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create();

        $response = $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('material_requests', [
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
            'notes' => null,
        ]);
    }

    public function test_new_material_request_has_pending_status(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create();

        $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        $this->assertDatabaseHas('material_requests', [
            'work_order_id' => $workOrder->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_creating_material_request_does_not_decrease_stock(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 3,
                    ],
                ],
            ]
        );

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);
    }

    public function test_creating_material_request_does_not_create_stock_movement(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $material->id,
                        'quantity' => 3,
                    ],
                ],
            ]
        );

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_material_request_can_contain_multiple_materials(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $materialA = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialB = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialC = Material::factory()->create([
            'stock' => 10,
        ]);

        $this->actingAs($mechanic)->post(
            route('mechanic.request-item.store'),
            [
                'work_order_id' => $workOrder->id,
                'items' => [
                    [
                        'material_id' => $materialA->id,
                        'quantity' => 1,
                    ],
                    [
                        'material_id' => $materialB->id,
                        'quantity' => 2,
                    ],
                    [
                        'material_id' => $materialC->id,
                        'quantity' => 3,
                    ],
                ],
            ]
        );

        $materialRequest = MaterialRequest::first();

        $this->assertNotNull($materialRequest);

        $this->assertDatabaseCount('material_request_items', 3);

        $this->assertDatabaseHas('material_request_items', [
            'material_request_id' => $materialRequest->id,
            'material_id' => $materialA->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseHas('material_request_items', [
            'material_request_id' => $materialRequest->id,
            'material_id' => $materialB->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('material_request_items', [
            'material_request_id' => $materialRequest->id,
            'material_id' => $materialC->id,
            'quantity' => 3,
        ]);
    }
}
