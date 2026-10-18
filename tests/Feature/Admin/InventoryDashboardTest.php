<?php

namespace Tests\Feature\Admin;

use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin aktif dapat mengakses dashboard inventory.
     */
    public function test_admin_can_access_inventory_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
    }

    /**
     * Dashboard menghitung seluruh material.
     */
    public function test_dashboard_displays_total_materials(): void
    {
        Material::factory()->count(3)->create();

        $response = $this->withoutMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertViewHas('totalMaterials', 3);
    }

    /**
     * Dashboard menampilkan material yang stoknya
     * kurang dari atau sama dengan minimum_stock.
     */
    public function test_dashboard_displays_low_stock_materials(): void
    {
        $lowStockMaterial = Material::factory()->create([
            'stock' => 5,
            'minimum_stock' => 10,
        ]);

        Material::factory()->create([
            'stock' => 20,
            'minimum_stock' => 10,
        ]);

        $equalStockMaterial = Material::factory()->create([
            'stock' => 10,
            'minimum_stock' => 10,
        ]);

        $response = $this->withoutMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'lowStockMaterials',
            function ($materials) use (
                $lowStockMaterial,
                $equalStockMaterial
            ) {
                return $materials->contains(
                    'id',
                    $lowStockMaterial->id
                )
                    && $materials->contains(
                        'id',
                        $equalStockMaterial->id
                    )
                    && $materials->count() === 2;
            }
        );
    }

    /**
     * Dashboard menghitung permintaan berstatus PENDING.
     */
    public function test_dashboard_counts_pending_material_requests(): void
    {
        MaterialRequest::factory()->count(2)->create([
            'status' => 'PENDING',
        ]);

        MaterialRequest::factory()->create([
            'status' => 'REJECTED',
        ]);

        $response = $this->withoutMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertViewHas('pendingRequests', 2);
    }

    /**
     * Dashboard menghitung stok masuk pada bulan berjalan.
     */
    public function test_dashboard_calculates_stock_in_this_month(): void
    {
        $this->createStockMovement(
            'IN',
            10,
            now()->startOfMonth()->addDay()
        );

        $this->createStockMovement(
            'IN',
            15,
            now()
        );

        $this->createStockMovement(
            'OUT',
            5,
            now()
        );

        $response = $this->withoutMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertViewHas('stockInThisMonth', 25);
    }

    /**
     * Dashboard menghitung stok keluar pada bulan berjalan.
     */
    public function test_dashboard_calculates_stock_out_this_month(): void
    {
        $this->createStockMovement(
            'OUT',
            4,
            now()->startOfMonth()->addDay()
        );

        $this->createStockMovement(
            'OUT',
            6,
            now()
        );

        $this->createStockMovement(
            'IN',
            20,
            now()
        );

        $response = $this->withoutMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertViewHas('stockOutThisMonth', 10);
    }

    /**
     * Dashboard menampilkan lima transaksi terbaru
     * dengan pagination.
     */
    public function test_dashboard_displays_recent_stock_movements(): void
    {
        for ($i = 0; $i < 7; $i++) {
            $this->createStockMovement('IN', 10);
        }

        $response = $this->withoutMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertViewHas('recentMovements');

        $movements = $response->viewData('recentMovements');

        $this->assertInstanceOf(
            \Illuminate\Pagination\LengthAwarePaginator::class,
            $movements
        );

        $this->assertCount(5, $movements->items());

        $this->assertSame(7, $movements->total());
    }

    /**
     * Dashboard menampilkan maksimal lima permintaan terbaru.
     */
    public function test_dashboard_displays_recent_material_requests(): void
    {
        MaterialRequest::factory()->count(7)->create();

        $response = $this->withoutMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertViewHas(
            'recentRequests',
            function ($requests) {
                return $requests->count() === 5;
            }
        );
    }

    /**
     * Membuat user Admin aktif untuk pengujian akses.
     */
    private function createAdmin(): User
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
        ]);

        return User::factory()->create([
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);
    }

    /**
     * Membuat transaksi stok untuk kebutuhan pengujian.
     */
    private function createStockMovement(
        string $type,
        int $quantity,
        $createdAt = null
    ): StockMovement {
        $material = Material::factory()->create();

        $user = $this->createAdmin();

        $attributes = [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => 100,
            'stock_after' => $type === 'IN'
                ? 100 + $quantity
                : 100 - $quantity,
            'notes' => 'Inventory dashboard test',
        ];

        if ($createdAt !== null) {
            $attributes['created_at'] = $createdAt;
            $attributes['updated_at'] = $createdAt;
        }

        return StockMovement::create($attributes);
    }
}
