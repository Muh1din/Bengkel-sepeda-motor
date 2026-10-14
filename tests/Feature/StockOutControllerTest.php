<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockOutControllerTest extends TestCase
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

    public function test_admin_can_stock_out_material(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-out.store'),
            [
                'material_id' => $material->id,
                'quantity' => 5,
                'notes' => 'Stock keluar untuk kebutuhan bengkel',
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock berhasil dikeluarkan.'
        );

        $this->assertEquals(
            5,
            $material->fresh()->stock
        );

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'OUT',
            'quantity' => 5,
            'stock_before' => 10,
            'stock_after' => 5,
            'notes' => 'Stock keluar untuk kebutuhan bengkel',
        ]);
    }

    public function test_material_id_is_required(): void
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-out.store'),
            [
                'quantity' => 5,
                'notes' => 'Stock keluar untuk kebutuhan bengkel',
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
            route('admin.stock-out.store'),
            [
                'material_id' => 999999,
                'quantity' => 5,
                'notes' => 'Stock keluar untuk kebutuhan bengkel',
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
            route('admin.stock-out.store'),
            [
                'material_id' => $material->id,
                'notes' => 'Stock keluar untuk kebutuhan bengkel',
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
            route('admin.stock-out.store'),
            [
                'material_id' => $material->id,
                'quantity' => 'abc',
                'notes' => 'Stock keluar untuk kebutuhan bengkel',
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
            route('admin.stock-out.store'),
            [
                'material_id' => $material->id,
                'quantity' => 0,
                'notes' => 'Stock keluar untuk kebutuhan bengkel',
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
            route('admin.stock-out.store'),
            [
                'material_id' => $material->id,
                'quantity' => 5,
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock berhasil dikeluarkan.'
        );

        $this->assertEquals(
            5,
            $material->fresh()->stock
        );

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'OUT',
            'quantity' => 5,
            'stock_before' => 10,
            'stock_after' => 5,
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
            route('admin.stock-out.store'),
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

    public function test_stock_out_cannot_exceed_available_stock(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-out.store'),
            [
                'material_id' => $material->id,
                'quantity' => 15,
                'notes' => 'Stock keluar untuk kebutuhan bengkel',
            ]
        );

        $this->assertEquals(
            10,
            $material->fresh()->stock
        );

        $this->assertDatabaseCount(
            'stock_movements',
            0
        );
    }

    public function test_stock_out_can_reduce_stock_to_zero(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-out.store'),
            [
                'material_id' => $material->id,
                'quantity' => 10,
                'notes' => 'Stock habis digunakan',
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock berhasil dikeluarkan.'
        );

        $this->assertEquals(
            0,
            $material->fresh()->stock
        );

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'OUT',
            'quantity' => 10,
            'stock_before' => 10,
            'stock_after' => 0,
            'notes' => 'Stock habis digunakan',
        ]);
    }

    public function test_non_admin_cannot_stock_out_material(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $mechanicRole = Role::factory()->create([
            'name' => 'Mechanic',
        ]);

        $user = User::factory()->create([
            'role_id' => $mechanicRole->id,
        ]);

        $response = $this->actingAs($user)->post(
            route('admin.stock-out.store'),
            [
                'material_id' => $material->id,
                'quantity' => 5,
                'notes' => 'Stock keluar untuk kebutuhan bengkel',
            ]
        );

        $response->assertForbidden();

        $this->assertEquals(
            10,
            $material->fresh()->stock
        );

        $this->assertDatabaseCount(
            'stock_movements',
            0
        );
    }
}
