<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockOutControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat user Admin untuk pengujian.
     */
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

    /**
     * Membuat transaksi stock movement untuk kebutuhan test.
     */
    private function createStockMovement(
        Material $material,
        User $user,
        string $type,
        int $quantity,
        int $stockBefore,
        int $stockAfter,
        ?string $notes = null,
        $createdAt = null,
    ): StockMovement {
        $movement = new StockMovement();

        $movement->material_id = $material->id;
        $movement->user_id = $user->id;
        $movement->type = $type;
        $movement->quantity = $quantity;
        $movement->stock_before = $stockBefore;
        $movement->stock_after = $stockAfter;
        $movement->notes = $notes;

        if ($createdAt !== null) {
            $movement->created_at = $createdAt;
            $movement->updated_at = $createdAt;
        }

        $movement->save();

        return $movement;
    }

    /**
     * Admin dapat mengakses halaman stock out.
     */
    public function test_admin_can_access_stock_out_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.stock-out.index'));

        $response->assertOk();

        $response->assertViewIs('admin.stock-out.index');

        $response->assertViewHasAll([
            'materialRequests',
            'stockOuts',
            'totalTransactions',
            'totalItemsThisMonth',
        ]);
    }

    /**
     * Halaman stock out hanya menampilkan permintaan berstatus PENDING.
     */
    public function test_stock_out_page_only_shows_pending_material_requests(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.stock-out.index'));

        $response->assertOk();

        $response->assertViewHas('materialRequests', function ($requests) {
            return $requests->every(
                fn($request) => $request->status === 'PENDING'
            );
        });
    }

    /**
     * Halaman stock out hanya menampilkan transaksi bertipe OUT.
     */
    public function test_stock_out_page_only_shows_out_movements(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $this->createStockMovement(
            $material,
            $admin,
            'OUT',
            2,
            10,
            8,
            'Material digunakan'
        );

        $this->createStockMovement(
            $material,
            $admin,
            'IN',
            5,
            5,
            10,
            'Stock masuk'
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.stock-out.index'));

        $response->assertOk();

        $response->assertViewHas('stockOuts', function ($stockOuts) {
            return $stockOuts->count() === 1
                && $stockOuts->every(
                    fn($movement) => $movement->type === 'OUT'
                );
        });
    }

    /**
     * Total transaksi hanya menghitung transaksi OUT.
     */
    public function test_stock_out_page_displays_total_transaction_count(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $this->createStockMovement(
            $material,
            $admin,
            'OUT',
            2,
            10,
            8
        );

        $this->createStockMovement(
            $material,
            $admin,
            'OUT',
            3,
            8,
            5
        );

        $this->createStockMovement(
            $material,
            $admin,
            'IN',
            5,
            5,
            10
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.stock-out.index'));

        $response->assertOk();

        $response->assertViewHas('totalTransactions', 2);
    }

    /**
     * Total item bulan ini menjumlahkan quantity transaksi OUT.
     */
    public function test_stock_out_page_counts_items_this_month(): void
    {
        $admin = $this->createAdmin();

        $material = Material::factory()->create([
            'stock' => 20,
        ]);

        $this->createStockMovement(
            $material,
            $admin,
            'OUT',
            3,
            20,
            17,
            'Pemakaian pertama',
            now()
        );

        $this->createStockMovement(
            $material,
            $admin,
            'OUT',
            4,
            17,
            13,
            'Pemakaian kedua',
            now()
        );

        $this->createStockMovement(
            $material,
            $admin,
            'IN',
            5,
            13,
            18,
            'Penambahan stok',
            now()
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.stock-out.index'));

        $response->assertOk();

        $response->assertViewHas('totalItemsThisMonth', 7);
    }

    /**
     * User aktif yang bukan Admin tidak dapat mengakses halaman stock out.
     */
    public function test_non_admin_cannot_access_stock_out_page(): void
    {
        $mechanicRole = Role::factory()->create([
            'name' => 'Mechanic',
        ]);

        $mechanic = User::factory()->create([
            'role_id' => $mechanicRole->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($mechanic)
            ->get(route('admin.stock-out.index'));

        $response->assertForbidden();
    }
}
