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

class RequestFulfillmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        $role = Role::firstOrCreate([
            'name' => 'Admin',
        ]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    private function createMechanic(): User
    {
        $role = Role::firstOrCreate([
            'name' => 'Mechanic',
        ]);

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    public function test_admin_can_fulfill_pending_material_request(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($admin)->post(
            route(
                'admin.material-requests.fulfill',
                $materialRequest
            )
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'FULFILLED',
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 8,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $admin->id,
            'type' => 'OUT',
            'quantity' => 2,
            'stock_before' => 10,
            'stock_after' => 8,
        ]);
    }


    public function test_fulfilling_material_request_processes_all_items(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $materialA = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialB = Material::factory()->create([
            'stock' => 20,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $materialA->id,
            'quantity' => 2,
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $materialB->id,
            'quantity' => 5,
        ]);

        $response = $this->actingAs($admin)->post(
            route(
                'admin.material-requests.fulfill',
                $materialRequest
            )
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('materials', [
            'id' => $materialA->id,
            'stock' => 8,
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $materialB->id,
            'stock' => 15,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $materialA->id,
            'type' => 'OUT',
            'quantity' => 2,
            'stock_before' => 10,
            'stock_after' => 8,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $materialB->id,
            'type' => 'OUT',
            'quantity' => 5,
            'stock_before' => 20,
            'stock_after' => 15,
        ]);

        $this->assertDatabaseCount('stock_movements', 2);

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'FULFILLED',
        ]);
    }

    public function test_fulfillment_fails_when_one_item_has_insufficient_stock(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $materialA = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialB = Material::factory()->create([
            'stock' => 3,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $materialA->id,
            'quantity' => 2,
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $materialB->id,
            'quantity' => 5,
        ]);

        $this->actingAs($admin)->post(
            route(
                'admin.material-requests.fulfill',
                $materialRequest
            )
        );

        $this->assertDatabaseHas('materials', [
            'id' => $materialA->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $materialB->id,
            'stock' => 3,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_fulfilled_material_request_cannot_be_fulfilled_again(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'FULFILLED',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $this->actingAs($admin)->post(
            route(
                'admin.material-requests.fulfill',
                $materialRequest
            )
        );

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'FULFILLED',
        ]);
    }

    public function test_rejected_material_request_cannot_be_fulfilled(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'REJECTED',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $this->actingAs($admin)->post(
            route(
                'admin.material-requests.fulfill',
                $materialRequest
            )
        );

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'REJECTED',
        ]);
    }

    public function test_admin_can_reject_pending_material_request(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($admin)->post(
            route(
                'admin.material-requests.reject',
                $materialRequest
            )
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'REJECTED',
        ]);
    }

    public function test_rejecting_material_request_does_not_decrease_stock(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($admin)->post(
            route(
                'admin.material-requests.reject',
                $materialRequest
            )
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_fulfilled_material_request_cannot_be_rejected(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'FULFILLED',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $this->actingAs($admin)->post(
            route(
                'admin.material-requests.reject',
                $materialRequest
            )
        );

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'FULFILLED',
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_rejected_material_request_cannot_be_rejected_again(): void
    {
        $admin = $this->createAdmin();

        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'REJECTED',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $this->actingAs($admin)->post(
            route(
                'admin.material-requests.reject',
                $materialRequest
            )
        );

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'REJECTED',
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_non_admin_cannot_fulfill_material_request(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $this->actingAs($mechanic)->post(
            route(
                'admin.material-requests.fulfill',
                $materialRequest
            )
        );

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'PENDING',
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_non_admin_cannot_reject_material_request(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $this->actingAs($mechanic)->post(
            route(
                'admin.material-requests.reject',
                $materialRequest
            )
        );

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'PENDING',
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_guest_cannot_fulfill_material_request(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $response = $this->post(
            route(
                'admin.material-requests.fulfill',
                $materialRequest
            )
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'PENDING',
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_guest_cannot_reject_material_request(): void
    {
        $mechanic = $this->createMechanic();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        MaterialRequestItem::factory()->create([
            'material_request_id' => $materialRequest->id,
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $response = $this->post(
            route(
                'admin.material-requests.reject',
                $materialRequest
            )
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('material_requests', [
            'id' => $materialRequest->id,
            'status' => 'PENDING',
        ]);

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);
    }
}
