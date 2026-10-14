<?php

namespace Tests\Feature\Admin;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockControllerTest extends TestCase
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

    public function test_admin_can_view_stock_index(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(
            route('admin.stock.index')
        );

        $response->assertOk();

        $response->assertViewIs('admin.stock.index');
    }

    public function test_admin_can_see_material_stock(): void
    {
        $admin = $this->createAdmin();

        $category = MaterialCategory::factory()->create();

        $material = Material::factory()->create([
            'material_category_id' => $category->id,
            'name' => 'Oli 10W-40',
            'stock' => 12,
            'minimum_stock' => 5,
            'unit' => 'botol',
        ]);

        $response = $this->actingAs($admin)->get(
            route('admin.stock.index')
        );

        $response->assertOk();

        $response->assertViewHas('materials', function ($materials) use ($material) {
            return $materials->contains($material);
        });
    }

    public function test_stock_index_loads_material_category_relationship(): void
    {
        $admin = $this->createAdmin();

        $category = MaterialCategory::factory()->create([
            'name' => 'Oli',
        ]);

        $material = Material::factory()->create([
            'material_category_id' => $category->id,
            'stock' => 12,
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($admin)->get(
            route('admin.stock.index')
        );

        $response->assertOk();

        $response->assertViewHas('materials', function ($materials) use (
            $material,
            $category
        ) {
            $stockMaterial = $materials->firstWhere(
                'id',
                $material->id
            );

            return $stockMaterial !== null
                && $stockMaterial->category->is($category);
        });
    }

    public function test_non_admin_cannot_view_stock_index(): void
    {
        $customerRole = Role::factory()->create([
            'name' => 'Customer',
        ]);

        $customer = User::factory()->create([
            'role_id' => $customerRole->id,
        ]);

        $response = $this->actingAs($customer)->get(
            route('admin.stock.index')
        );

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_stock_index(): void
    {
        $response = $this->get(
            route('admin.stock.index')
        );

        $response->assertRedirect();
    }
}
