<?php

namespace Tests\Feature\Admin;

use App\Models\MaterialCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat user Admin untuk pengujian.
     */
    private function createAdmin(): User
    {
        $role = \App\Models\Role::firstOrCreate([
            'name' => 'Admin',
        ]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    /**
     * Membuat user Mechanic untuk pengujian hak akses.
     */
    private function createMechanic(): User
    {
        $role = \App\Models\Role::firstOrCreate([
            'name' => 'Mechanic',
        ]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    /**
     * Membuat kategori untuk kebutuhan pengujian.
     */
    private function createCategory(array $attributes = []): MaterialCategory
    {
        return MaterialCategory::create(array_merge([
            'name' => 'Oli Mesin',
            'description' => 'Kategori oli untuk sepeda motor.',
        ], $attributes));
    }

    // ==========================================
    // INDEX
    // ==========================================

    public function test_admin_can_access_category_index(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.categories.index'));

        $response->assertOk();
        $response->assertViewIs('admin.categories.index');
    }

    public function test_category_index_displays_categories_sorted_by_name(): void
    {
        $admin = $this->createAdmin();

        $this->createCategory(['name' => 'Sparepart']);
        $this->createCategory(['name' => 'Aksesori']);
        $this->createCategory(['name' => 'Oli Mesin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.categories.index'));

        $response->assertOk();

        $categories = $response->viewData('categories');

        $this->assertSame(
            ['Aksesori', 'Oli Mesin', 'Sparepart'],
            $categories->pluck('name')->all()
        );
    }

    // ==========================================
    // CREATE
    // ==========================================

    public function test_admin_can_access_create_category_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.categories.create'));

        $response->assertOk();
        $response->assertViewIs('admin.categories.create');
    }

    // ==========================================
    // STORE
    // ==========================================

    public function test_admin_can_create_category(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Oli Mesin',
                'description' => 'Kategori oli untuk sepeda motor.',
            ]);

        $response->assertRedirect(route('admin.categories.index'));

        $response->assertSessionHas(
            'success',
            'Kategori berhasil ditambahkan.'
        );

        $this->assertDatabaseHas('material_categories', [
            'name' => 'Oli Mesin',
            'description' => 'Kategori oli untuk sepeda motor.',
        ]);
    }

    public function test_category_name_is_required(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => '',
                'description' => 'Deskripsi kategori.',
            ]);

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('material_categories', [
            'description' => 'Deskripsi kategori.',
        ]);
    }

    public function test_category_name_must_be_a_string(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => ['Oli Mesin'],
                'description' => 'Deskripsi kategori.',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_category_name_cannot_exceed_255_characters(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => str_repeat('a', 256),
                'description' => 'Deskripsi kategori.',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_category_name_must_be_unique(): void
    {
        $admin = $this->createAdmin();

        $this->createCategory([
            'name' => 'Oli Mesin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Oli Mesin',
                'description' => 'Kategori lainnya.',
            ]);

        $response->assertSessionHasErrors('name');

        $this->assertSame(
            1,
            MaterialCategory::where('name', 'Oli Mesin')->count()
        );
    }

    public function test_category_description_is_optional(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Filter Udara',
                'description' => null,
            ]);

        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('material_categories', [
            'name' => 'Filter Udara',
            'description' => null,
        ]);
    }

    // ==========================================
    // EDIT
    // ==========================================

    public function test_admin_can_access_edit_category_page(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $response = $this->actingAs($admin)
            ->get(route('admin.categories.edit', $category));

        $response->assertOk();
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category', $category);
    }

    public function test_edit_page_returns_404_for_nonexistent_category(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.categories.edit', 999999));

        $response->assertNotFound();
    }

    // ==========================================
    // UPDATE
    // ==========================================

    public function test_admin_can_update_category(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $response = $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Oli Transmisi',
                'description' => 'Kategori oli transmisi.',
            ]);

        $response->assertRedirect(route('admin.categories.index'));

        $response->assertSessionHas(
            'success',
            'Kategori berhasil diperbarui.'
        );

        $this->assertDatabaseHas('material_categories', [
            'id' => $category->id,
            'name' => 'Oli Transmisi',
            'description' => 'Kategori oli transmisi.',
        ]);
    }

    public function test_category_can_keep_its_existing_name_when_updated(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory([
            'name' => 'Oli Mesin',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Oli Mesin',
                'description' => 'Deskripsi diperbarui.',
            ]);

        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('material_categories', [
            'id' => $category->id,
            'name' => 'Oli Mesin',
            'description' => 'Deskripsi diperbarui.',
        ]);
    }

    public function test_category_name_must_be_unique_when_updating(): void
    {
        $admin = $this->createAdmin();

        $this->createCategory([
            'name' => 'Oli Mesin',
        ]);

        $category = $this->createCategory([
            'name' => 'Filter Udara',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Oli Mesin',
                'description' => 'Deskripsi kategori.',
            ]);

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseHas('material_categories', [
            'id' => $category->id,
            'name' => 'Filter Udara',
        ]);
    }

    public function test_category_name_is_required_when_updating(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $response = $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => '',
                'description' => 'Deskripsi kategori.',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_update_returns_404_for_nonexistent_category(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->put(route('admin.categories.update', 999999), [
                'name' => 'Kategori Baru',
                'description' => 'Deskripsi kategori.',
            ]);

        $response->assertNotFound();
    }

    // ==========================================
    // DESTROY
    // ==========================================

    public function test_admin_can_delete_category(): void
    {
        $admin = $this->createAdmin();

        $category = $this->createCategory();

        $response = $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));

        $response->assertSessionHas(
            'success',
            'Kategori berhasil dihapus.'
        );

        $this->assertDatabaseMissing('material_categories', [
            'id' => $category->id,
        ]);
    }

    public function test_delete_returns_404_for_nonexistent_category(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', 999999));

        $response->assertNotFound();
    }

    // ==========================================
    // AUTHENTICATION & AUTHORIZATION
    // ==========================================

    public function test_guest_cannot_access_category_index(): void
    {
        $response = $this->get(route('admin.categories.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_create_category(): void
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Oli Mesin',
            'description' => 'Kategori oli.',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('material_categories', [
            'name' => 'Oli Mesin',
        ]);
    }

    public function test_non_admin_cannot_access_category_index(): void
    {
        $mechanic = $this->createMechanic();

        $response = $this->actingAs($mechanic)
            ->get(route('admin.categories.index'));

        $response->assertForbidden();
    }

    public function test_non_admin_cannot_create_category(): void
    {
        $mechanic = $this->createMechanic();

        $response = $this->actingAs($mechanic)
            ->post(route('admin.categories.store'), [
                'name' => 'Oli Mesin',
                'description' => 'Kategori oli.',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('material_categories', [
            'name' => 'Oli Mesin',
        ]);
    }
}
