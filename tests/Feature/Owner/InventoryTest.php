<?php

namespace Tests\Feature\Owner;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private int $ownerRoleId;

    private int $sequence = 0;

    protected function setUp(): void
    {
        parent::setUp();

        // Menggunakan role yang sudah dibuat oleh RoleSeeder.
        $ownerRole = Role::where('name', 'Owner')->firstOrFail();

        $this->ownerRoleId = $ownerRole->id;

        $this->owner = User::factory()->create([
            'role_id' => $this->ownerRoleId,
            'is_active' => true
        ]);
    }

    /**
     * Membuat kategori material untuk kebutuhan test.
     */
    private function createCategory(): MaterialCategory
    {
        return MaterialCategory::create([
            'name' => 'Kategori Test ' . Str::uuid(),
            'description' => 'Kategori untuk pengujian inventory.',
        ]);
    }

    /**
     * Membuat material dengan stok yang ditentukan.
     *
     * Menggunakan forceCreate karena atribut stock pada model
     * Material mungkin tidak termasuk dalam $fillable.
     */
    private function createMaterial(
        MaterialCategory $category,
        int $stock = 10,
        int $minimumStock = 5
    ): Material {
        $this->sequence++;

        return Material::forceCreate([
            'material_category_id' => $category->id,
            'name' => sprintf('Material Test %03d', $this->sequence),
            'barcode' => 'TEST-' . Str::uuid(),
            'unit' => 'pcs',
            'stock' => $stock,
            'minimum_stock' => $minimumStock,
            'description' => 'Material untuk pengujian inventory.',
        ]);
    }

    /**
     * Membuat pergerakan stok.
     */
    private function createStockMovement(
        Material $material,
        string $type = 'IN',
        int $quantity = 1
    ): StockMovement {
        return StockMovement::forceCreate([
            'material_id' => $material->id,
            'user_id' => $this->owner->id,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => 10,
            'stock_after' => 10 + $quantity,
            'notes' => 'Pergerakan stok untuk pengujian.',
        ]);
    }

    public function test_owner_can_open_inventory_index(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('owner.inventory.index'));

        $response->assertOk()
            ->assertViewIs('owner.inventory.index')
            ->assertViewHasAll([
                'statistics',
                'materials',
                'recentMovements',
            ]);
    }

    public function test_inventory_statistics_are_calculated_correctly(): void
    {
        $category = $this->createCategory();

        // Baseline diperlukan karena DatabaseSeeder tetap berjalan.
        $baselineTotalMaterials = Material::count();

        $baselineLowStock = Material::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )
            ->where('stock', '>', 0)
            ->count();

        $baselineOutOfStock = Material::where('stock', 0)->count();

        $baselineMovements = StockMovement::count();

        // Material dengan stok normal.
        $this->createMaterial($category, 20, 5);

        // Material dengan stok rendah, tetapi tidak kosong.
        $this->createMaterial($category, 3, 5);

        // Material dengan stok kosong.
        $this->createMaterial($category, 0, 5);

        // Material dengan stok tepat pada batas minimum.
        $this->createMaterial($category, 5, 5);

        $material = Material::where(
            'name',
            'like',
            'Material Test%'
        )->latest('id')->firstOrFail();

        $this->createStockMovement($material, 'IN', 2);
        $this->createStockMovement($material, 'OUT', 1);

        $response = $this->actingAs($this->owner)
            ->get(route('owner.inventory.index'))
            ->assertOk();

        $statistics = $response->viewData('statistics');

        $this->assertSame(
            $baselineTotalMaterials + 4,
            $statistics['total_materials']
        );

        $this->assertSame(
            $baselineLowStock + 2,
            $statistics['low_stock_materials']
        );

        $this->assertSame(
            $baselineOutOfStock + 1,
            $statistics['out_of_stock_materials']
        );

        $this->assertSame(
            $baselineMovements + 2,
            $statistics['total_stock_movements']
        );
    }

    public function test_inventory_materials_are_paginated_ten_per_page(): void
    {
        $category = $this->createCategory();

        $baselineTotal = Material::count();

        for ($i = 0; $i < 12; $i++) {
            $this->createMaterial($category);
        }

        $response = $this->actingAs($this->owner)
            ->get(route('owner.inventory.index'))
            ->assertOk();

        $materials = $response->viewData('materials');

        $this->assertSame(10, $materials->perPage());

        $this->assertSame(
            $baselineTotal + 12,
            $materials->total()
        );

        $this->assertSame(10, $materials->count());

        $response = $this->actingAs($this->owner)
            ->get(route('owner.inventory.index', ['page' => 2]))
            ->assertOk();

        $pageTwo = $response->viewData('materials');

        $this->assertSame(2, $pageTwo->count());
        $this->assertSame(2, $pageTwo->currentPage());
    }

    public function test_inventory_materials_include_their_category(): void
    {
        $category = $this->createCategory();

        $material = $this->createMaterial($category);

        $response = $this->actingAs($this->owner)
            ->get(route('owner.inventory.index'))
            ->assertOk();

        $materials = $response->viewData('materials');

        $loadedMaterial = $materials->getCollection()
            ->firstWhere('id', $material->id);

        $this->assertNotNull($loadedMaterial);

        // Memastikan eager loading relasi category bekerja.
        $this->assertTrue(
            $loadedMaterial->relationLoaded('category')
        );

        $this->assertSame(
            $category->id,
            $loadedMaterial->category->id
        );
    }

    public function test_inventory_displays_only_ten_recent_stock_movements(): void
    {
        $category = $this->createCategory();

        $material = $this->createMaterial($category);

        $baselineMovements = StockMovement::count();

        for ($i = 0; $i < 12; $i++) {
            $this->createStockMovement($material, 'IN', $i + 1);
        }

        $response = $this->actingAs($this->owner)
            ->get(route('owner.inventory.index'))
            ->assertOk();

        $recentMovements = $response->viewData('recentMovements');

        $this->assertCount(10, $recentMovements);

        $this->assertSame(
            $baselineMovements + 12,
            StockMovement::count()
        );

        $this->assertTrue(
            $recentMovements->every(
                fn(StockMovement $movement) =>
                $movement->relationLoaded('material')
                    && $movement->relationLoaded('user')
            )
        );

        $this->assertTrue(
            $recentMovements->first()->created_at >=
                $recentMovements->last()->created_at
        );
    }

    public function test_guest_cannot_access_inventory_index(): void
    {
        $this->get(route('owner.inventory.index'))
            ->assertRedirect(route('login'));
    }
}
