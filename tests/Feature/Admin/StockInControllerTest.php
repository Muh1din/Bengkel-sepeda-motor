<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockInControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        $adminRole = Role::factory()->create([
            'name' => 'Admin',
        ]);

        return User::factory()->create([
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_stock_in_material(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'material_id' => $material->id,
                'quantity' => 5,
                'notes' => 'Stock masuk dari supplier',
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock In berhasil diproses.'
        );

        $this->assertEquals(15, $material->fresh()->stock);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'IN',
            'quantity' => 5,
            'stock_before' => 10,
            'stock_after' => 15,
            'notes' => 'Stock masuk dari supplier',
        ]);
    }

    public function test_material_id_is_required(): void
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'quantity' => 5,
                'notes' => 'Stock masuk dari supplier',
            ]
        );

        $response->assertSessionHasErrors([
            'material_id',
        ]);
    }

    public function test_material_id_must_exist(): void
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'material_id' => 999999,
                'quantity' => 5,
                'notes' => 'Stock masuk dari supplier',
            ]
        );

        $response->assertSessionHasErrors([
            'material_id',
        ]);
    }

    public function test_quantity_is_required(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'material_id' => $material->id,
                'notes' => 'Stock masuk dari supplier',
            ]
        );

        $response->assertSessionHasErrors([
            'quantity',
        ]);
    }

    public function test_quantity_must_be_integer(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'material_id' => $material->id,
                'quantity' => 'abc',
                'notes' => 'Stock masuk dari supplier',
            ]
        );

        $response->assertSessionHasErrors([
            'quantity',
        ]);
    }

    public function test_quantity_must_be_at_least_one(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'material_id' => $material->id,
                'quantity' => 0,
                'notes' => 'Stock masuk dari supplier',
            ]
        );

        $response->assertSessionHasErrors([
            'quantity',
        ]);
    }

    public function test_notes_is_optional(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'material_id' => $material->id,
                'quantity' => 5,
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock In berhasil diproses.'
        );

        $this->assertEquals(15, $material->fresh()->stock);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'IN',
            'quantity' => 5,
            'stock_before' => 10,
            'stock_after' => 15,
            'notes' => null,
        ]);
    }

    public function test_notes_must_be_string(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'material_id' => $material->id,
                'quantity' => 5,
                'notes' => 12345,
            ]
        );

        $response->assertSessionHasErrors([
            'notes',
        ]);
    }

    public function test_non_admin_cannot_stock_in_material(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $mechanicRole = Role::factory()->create([
            'name' => 'Mechanic',
        ]);

        $user = User::factory()->create([
            'role_id' => $mechanicRole->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(
            route('admin.stock-in.store'),
            [
                'material_id' => $material->id,
                'quantity' => 5,
                'notes' => 'Stock masuk dari supplier',
            ]
        );

        $response->assertForbidden();

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }
}
