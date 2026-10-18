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
            'is_active' => true,
        ]);
    }

    private function createMechanic(): User
    {
        $mechanicRole = Role::factory()->create([
            'name' => 'Mechanic',
        ]);

        return User::factory()->create([
            'role_id' => $mechanicRole->id,
            'is_active' => true,
        ]);
    }

    /**
     * Admin dapat menyesuaikan stok material.
     */
    public function test_admin_can_adjust_material_stock(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 7,
                'notes' => 'Hasil pengecekan stok fisik',
            ]
        );

        $response->assertRedirect(route('admin.stock-adjustments.index'));

        $response->assertSessionHas(
            'success',
            'Stok berhasil disesuaikan.'
        );

        $this->assertEquals(7, $material->fresh()->stock);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $admin->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 3,
            'stock_before' => 10,
            'stock_after' => 7,
            'notes' => 'Hasil pengecekan stok fisik',
        ]);
    }

    /**
     * material_id wajib diisi.
     */
    public function test_material_id_is_required(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'physical_stock' => 7,
                'notes' => 'Pengecekan stok',
            ]
        );

        $response->assertSessionHasErrors('material_id');
    }

    /**
     * material_id harus menunjuk material yang tersedia.
     */
    public function test_material_id_must_exist(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => 999999,
                'physical_stock' => 7,
                'notes' => 'Pengecekan stok',
            ]
        );

        $response->assertSessionHasErrors('material_id');
    }

    /**
     * physical_stock wajib diisi.
     */
    public function test_physical_stock_is_required(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
            ]
        );

        $response->assertSessionHasErrors('physical_stock');
    }

    /**
     * physical_stock harus berupa bilangan bulat.
     */
    public function test_physical_stock_must_be_integer(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 'abc',
            ]
        );

        $response->assertSessionHasErrors('physical_stock');
    }

    /**
     * physical_stock tidak boleh negatif.
     */
    public function test_physical_stock_cannot_be_negative(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => -1,
            ]
        );

        $response->assertSessionHasErrors('physical_stock');
    }

    /**
     * Catatan boleh dikosongkan.
     */
    public function test_notes_is_optional(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 7,
            ]
        );

        $response->assertRedirect(route('admin.stock-adjustments.index'));

        $response->assertSessionHas(
            'success',
            'Stok berhasil disesuaikan.'
        );

        $this->assertEquals(7, $material->fresh()->stock);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $admin->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 3,
            'stock_before' => 10,
            'stock_after' => 7,
            'notes' => null,
        ]);
    }

    /**
     * Catatan harus berupa string.
     */
    public function test_notes_must_be_string(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 7,
                'notes' => ['Catatan tidak valid'],
            ]
        );

        $response->assertSessionHasErrors('notes');
    }

    /**
     * Catatan tidak boleh melebihi 500 karakter.
     */
    public function test_notes_cannot_exceed_500_characters(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 7,
                'notes' => str_repeat('a', 501),
            ]
        );

        $response->assertSessionHasErrors('notes');
    }

    /**
     * Stok dapat bertambah.
     */
    public function test_adjustment_can_increase_stock(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 15,
                'notes' => 'Ditemukan stok tambahan',
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stok berhasil disesuaikan.'
        );

        $this->assertEquals(15, $material->fresh()->stock);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $admin->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 5,
            'stock_before' => 10,
            'stock_after' => 15,
        ]);
    }

    /**
     * Stok dapat disesuaikan menjadi nol.
     */
    public function test_adjustment_can_reduce_stock_to_zero(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 0,
                'notes' => 'Stok fisik kosong',
            ]
        );

        $response->assertSessionHas(
            'success',
            'Stok berhasil disesuaikan.'
        );

        $this->assertEquals(0, $material->fresh()->stock);

        $this->assertDatabaseHas('stock_movements', [
            'material_id' => $material->id,
            'user_id' => $admin->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 10,
            'stock_before' => 10,
            'stock_after' => 0,
        ]);
    }

    /**
     * Penyesuaian ditolak jika stok tidak berubah.
     */
    public function test_adjustment_is_rejected_when_stock_is_unchanged(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 10,
                'notes' => 'Tidak ada perubahan stok',
            ]
        );

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    /**
     * Mekanik tidak boleh menyesuaikan stok.
     */
    public function test_non_admin_cannot_adjust_material_stock(): void
    {
        $mechanic = $this->createMechanic();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($mechanic)->post(
            route('admin.stock-adjustments.store'),
            [
                'material_id' => $material->id,
                'physical_stock' => 5,
                'notes' => 'Percobaan adjustment',
            ]
        );

        $response->assertForbidden();

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }
}
