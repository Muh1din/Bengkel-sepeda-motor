<?php

namespace Tests\Feature\Admin;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat user Admin aktif.
     */
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

    /**
     * Helper untuk membuat kategori material.
     */
    private function createCategory(): MaterialCategory
    {
        return MaterialCategory::factory()->create();
    }

    /**
     * Admin dapat melihat daftar material.
     */
    public function test_admin_can_view_material_index(): void
    {
        $admin = $this->createAdmin();

        Material::factory()->count(3)->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.materials.index'));

        $response->assertOk();

        $response->assertViewIs('admin.materials.index');

        $response->assertViewHas('materials');
    }

    /**
     * Admin dapat membuka form tambah material.
     */
    public function test_admin_can_view_create_material_form(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.materials.create'));

        $response->assertOk();

        $response->assertViewIs('admin.materials.create');

        $response->assertViewHas('categories');
    }

    /**
     * Admin dapat menambahkan material baru.
     */
    public function test_admin_can_create_material(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $response = $this->actingAs($admin)
            ->post(route('admin.materials.store'), [
                'name' => 'Oli Mesin',
                'material_category_id' => $category->id,
                'barcode' => 'MAT-TEST-001',
                'unit' => 'botol',
                'minimum_stock' => 5,
            ]);

        $response->assertRedirect(
            route('admin.materials.index')
        );

        $response->assertSessionHas(
            'success',
            'Material berhasil ditambahkan.'
        );

        $this->assertDatabaseHas('materials', [
            'name' => 'Oli Mesin',
            'material_category_id' => $category->id,
            'barcode' => 'MAT-TEST-001',
            'unit' => 'botol',
            'minimum_stock' => 5,
            'stock' => 0,
        ]);
    }

    /**
     * Material baru harus memiliki stok awal nol.
     */
    public function test_new_material_has_zero_initial_stock(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $this->actingAs($admin)
            ->post(route('admin.materials.store'), [
                'name' => 'Kampas Rem',
                'material_category_id' => $category->id,
                'barcode' => 'MAT-TEST-002',
                'unit' => 'pcs',
                'minimum_stock' => 3,
            ]);

        $this->assertDatabaseHas('materials', [
            'name' => 'Kampas Rem',
            'stock' => 0,
        ]);
    }

    /**
     * Penambahan material ditolak jika nama kosong.
     */
    public function test_material_creation_requires_name(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $response = $this->actingAs($admin)
            ->post(route('admin.materials.store'), [
                'name' => '',
                'material_category_id' => $category->id,
                'barcode' => 'MAT-TEST-003',
                'unit' => 'pcs',
                'minimum_stock' => 5,
            ]);

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('materials', [
            'barcode' => 'MAT-TEST-003',
        ]);
    }

    /**
     * Penambahan material ditolak jika kategori tidak valid.
     */
    public function test_material_creation_requires_valid_category(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.materials.store'), [
                'name' => 'Oli Mesin',
                'material_category_id' => 999999,
                'barcode' => 'MAT-TEST-004',
                'unit' => 'botol',
                'minimum_stock' => 5,
            ]);

        $response->assertSessionHasErrors(
            'material_category_id'
        );
    }

    /**
     * Barcode tidak boleh duplikat.
     */
    public function test_material_creation_rejects_duplicate_barcode(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        Material::factory()->create([
            'material_category_id' => $category->id,
            'barcode' => 'MAT-DUPLICATE',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.materials.store'), [
                'name' => 'Oli Baru',
                'material_category_id' => $category->id,
                'barcode' => 'MAT-DUPLICATE',
                'unit' => 'botol',
                'minimum_stock' => 5,
            ]);

        $response->assertSessionHasErrors('barcode');
    }

    /**
     * Minimum stok tidak boleh negatif.
     */
    public function test_material_creation_rejects_negative_minimum_stock(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $response = $this->actingAs($admin)
            ->post(route('admin.materials.store'), [
                'name' => 'Filter Udara',
                'material_category_id' => $category->id,
                'barcode' => 'MAT-TEST-005',
                'unit' => 'pcs',
                'minimum_stock' => -1,
            ]);

        $response->assertSessionHasErrors('minimum_stock');
    }

    /**
     * Admin dapat membuka form edit material.
     */
    public function test_admin_can_view_edit_material_form(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.materials.edit', $material));

        $response->assertOk();

        $response->assertViewIs('admin.materials.edit');

        $response->assertViewHas('material');

        $response->assertViewHas('categories');
    }

    /**
     * Admin dapat memperbarui material.
     */
    public function test_admin_can_update_material(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $material = Material::factory()->create([
            'name' => 'Oli Lama',
            'material_category_id' => $category->id,
            'barcode' => 'MAT-OLD-001',
            'unit' => 'botol',
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.materials.update', $material), [
                'name' => 'Oli Mesin Premium',
                'category_id' => $category->id,
                'barcode' => 'MAT-OLD-001',
                'unit' => 'botol',
                'minimum_stock' => 8,
            ]);

        $response->assertRedirect(
            route('admin.materials.index')
        );

        $response->assertSessionHas(
            'success',
            'Material berhasil diperbarui.'
        );

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'name' => 'Oli Mesin Premium',
            'material_category_id' => $category->id,
            'barcode' => 'MAT-OLD-001',
            'minimum_stock' => 8,
        ]);
    }

    /**
     * Barcode material yang sama boleh dipertahankan
     * ketika material tersebut diperbarui.
     */
    public function test_material_update_allows_own_barcode(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $material = Material::factory()->create([
            'material_category_id' => $category->id,
            'barcode' => 'MAT-OWN-001',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.materials.update', $material), [
                'name' => $material->name,
                'category_id' => $category->id,
                'barcode' => 'MAT-OWN-001',
                'unit' => $material->unit,
                'minimum_stock' => $material->minimum_stock,
            ]);

        $response->assertRedirect(
            route('admin.materials.index')
        );

        $response->assertSessionHasNoErrors();
    }

    /**
     * Admin dapat menghapus material.
     */
    public function test_admin_can_delete_material(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('admin.materials.destroy', $material));

        $response->assertRedirect(
            route('admin.materials.index')
        );

        $response->assertSessionHas(
            'success',
            'Material berhasil dihapus.'
        );

        $this->assertDatabaseMissing('materials', [
            'id' => $material->id,
        ]);
    }
}
