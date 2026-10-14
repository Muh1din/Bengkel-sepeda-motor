<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\Material;
use App\Models\MaterialRequestItem;
use App\Models\MaterialRequest;
use App\Models\WorkOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        $adminRole = Role::factory()->create([
            'name' => 'Admin',
        ]);

        return User::factory()->create([
            'role_id' => $adminRole->id,
        ]);
    }

    public function test_admin_can_view_material_request_index(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(
            route('admin.material-requests.index')
        );

        $response->assertOk();
        $response->assertViewIs('admin.material-requests.index');
    }

    public function test_admin_can_see_material_requests(): void
    {
        $admin = $this->createAdmin();

        $mechanic = User::factory()->mechanic()->create();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $materialRequest = MaterialRequest::factory()->create([
            'work_order_id' => $workOrder->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'PENDING',
        ]);

        $response = $this->actingAs($admin)->get(
            route('admin.material-requests.index')
        );

        $response->assertOk();

        $response->assertViewHas('materialRequests', function ($materialRequests) use ($materialRequest) {
            return $materialRequests->contains($materialRequest);
        });
    }

    public function test_admin_can_see_material_request_relationships(): void
    {
        $admin = $this->createAdmin();

        $mechanic = User::factory()->mechanic()->create();

        $workOrder = WorkOrder::factory()->create([
            'mechanic_id' => $mechanic->id,
        ]);

        $material = Material::factory()->create();

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

        $response = $this->actingAs($admin)->get(
            route('admin.material-requests.index')
        );

        $response->assertOk();

        $response->assertViewHas('materialRequests', function ($materialRequests) use (
            $materialRequest,
            $workOrder,
            $mechanic
        ) {
            $request = $materialRequests->firstWhere(
                'id',
                $materialRequest->id
            );

            return $request !== null
                && $request->workOrder->is($workOrder)
                && $request->mechanic->is($mechanic)
                && $request->items->count() === 1;
        });
    }
}
