<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockAdjustmentControllerTest extends TestCase
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

    public function test_admin_can_adjust_material_stock(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 7,
                'notes' => 'Hasil pengecekan stok fisik',
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock berhasil disesuaikan.'
        );

        $this->assertEquals(
            7,
            $material->fresh()->stock
        );

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 3,
            'stock_before' => 10,
            'stock_after' => 7,
            'notes' => 'Hasil pengecekan stok fisik',
        ]);
    }

    public function test_material_id_is_required(): void
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'physical_stock' => 7,
                'notes' => 'Hasil pengecekan stok fisik',
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
            route('admin.stock-adjustment.store'),
            [
                'material_id' => 999999,
                'physical_stock' => 7,
                'notes' => 'Hasil pengecekan stok fisik',
            ]
        );

        $response->assertSessionHasErrors([
            'material_id',
        ]);
    }

    public function test_physical_stock_is_required(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'notes' => 'Hasil pengecekan stok fisik',
            ]
        );

        $response->assertSessionHasErrors([
            'physical_stock',
        ]);
    }

    public function test_physical_stock_must_be_integer(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 'abc',
                'notes' => 'Hasil pengecekan stok fisik',
            ]
        );

        $response->assertSessionHasErrors([
            'physical_stock',
        ]);
    }

    public function test_physical_stock_cannot_be_negative(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => -1,
                'notes' => 'Hasil pengecekan stok fisik',
            ]
        );

        $response->assertSessionHasErrors([
            'physical_stock',
        ]);
    }

    public function test_notes_is_optional(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 7,
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock berhasil disesuaikan.'
        );

        $this->assertEquals(
            7,
            $material->fresh()->stock
        );

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 3,
            'stock_before' => 10,
            'stock_after' => 7,
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
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 7,
                'notes' => 12345,
            ]
        );

        $response->assertSessionHasErrors([
            'notes',
        ]);
    }

    public function test_adjustment_can_increase_stock(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 15,
                'notes' => 'Ditemukan stok tambahan',
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock berhasil disesuaikan.'
        );

        $this->assertEquals(
            15,
            $material->fresh()->stock
        );

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 5,
            'stock_before' => 10,
            'stock_after' => 15,
        ]);
    }

    public function test_adjustment_can_reduce_stock_to_zero(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 0,
                'notes' => 'Stok fisik kosong',
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stock berhasil disesuaikan.'
        );

        $this->assertEquals(
            0,
            $material->fresh()->stock
        );

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 10,
            'stock_before' => 10,
            'stock_after' => 0,
        ]);
    }

    public function test_adjustment_is_rejected_when_stock_is_unchanged(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = $this->createAdmin();

        $this->actingAs($user)->post(
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 10,
                'notes' => 'Tidak ada perubahan stok',
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

    public function test_non_admin_cannot_adjust_material_stock(): void
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
            route('admin.stock-adjustment.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 5,
                'notes' => 'Percobaan adjustment',
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
