<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\StockMovementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_in_increases_material_stock_and_creates_movement(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        $movement = $service->stockIn(
            $material,
            5,
            $user,
            'Stock masuk dari supplier'
        );

        $this->assertEquals(15, $material->fresh()->stock);

        $this->assertEquals('IN', $movement->type);
        $this->assertEquals(5, $movement->quantity);
        $this->assertEquals(10, $movement->stock_before);
        $this->assertEquals(15, $movement->stock_after);
        $this->assertEquals($user->id, $movement->user_id);
        $this->assertEquals($material->id, $movement->material_id);

        $this->assertDatabaseHas('stock_movements', [
            'id' => $movement->id,
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'IN',
            'quantity' => 5,
            'stock_before' => 10,
            'stock_after' => 15,
            'notes' => 'Stock masuk dari supplier',
        ]);
    }

    public function test_stock_in_rejects_zero_quantity(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        try {
            $service->stockIn(
                $material,
                0,
                $user
            );

            $this->fail('Stock in with zero quantity should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                'Stock in quantity must be greater than zero.',
                $e->getMessage()
            );
        }

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_stock_in_rejects_negative_quantity(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        try {
            $service->stockIn(
                $material,
                -5,
                $user
            );

            $this->fail('Stock in with negative quantity should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                'Stock in quantity must be greater than zero.',
                $e->getMessage()
            );
        }

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_stock_out_decreases_material_stock_and_creates_movement(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        $movement = $service->stockOut(
            $material,
            3,
            $user,
            null,
            'Material digunakan'
        );

        $this->assertEquals(7, $material->fresh()->stock);

        $this->assertEquals('OUT', $movement->type);
        $this->assertEquals(3, $movement->quantity);
        $this->assertEquals(10, $movement->stock_before);
        $this->assertEquals(7, $movement->stock_after);
        $this->assertEquals($user->id, $movement->user_id);
        $this->assertEquals($material->id, $movement->material_id);

        $this->assertDatabaseHas('stock_movements', [
            'id' => $movement->id,
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'OUT',
            'quantity' => 3,
            'stock_before' => 10,
            'stock_after' => 7,
            'notes' => 'Material digunakan',
        ]);
    }

    public function test_stock_out_rejects_when_quantity_exceeds_stock(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        try {
            $service->stockOut(
                $material,
                11,
                $user,
                null,
                'Material digunakan'
            );

            $this->fail(
                'Stock out should fail when quantity exceeds available stock.'
            );
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Insufficient stock.', $e->getMessage());
        }

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_stock_out_rejects_zero_quantity(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        try {
            $service->stockOut($material, 0, $user);

            $this->fail('Stock out with zero quantity should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                'Stock out quantity must be greater than zero.',
                $e->getMessage()
            );
        }

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_stock_out_rejects_negative_quantity(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        try {
            $service->stockOut($material, -5, $user);

            $this->fail('Stock out with negative quantity should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                'Stock out quantity must be greater than zero.',
                $e->getMessage()
            );
        }

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_stock_out_can_reduce_stock_to_zero(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        $movement = $service->stockOut(
            $material,
            10,
            $user,
            null,
            'Semua material digunakan'
        );

        $this->assertEquals(0, $material->fresh()->stock);

        $this->assertEquals('OUT', $movement->type);
        $this->assertEquals(10, $movement->quantity);
        $this->assertEquals(10, $movement->stock_before);
        $this->assertEquals(0, $movement->stock_after);

        $this->assertDatabaseHas('stock_movements', [
            'id' => $movement->id,
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'OUT',
            'quantity' => 10,
            'stock_before' => 10,
            'stock_after' => 0,
            'notes' => 'Semua material digunakan',
        ]);
    }

    // Adjustment
    public function test_adjustment_decreases_stock_to_physical_stock_and_creates_movement(): void
    {
        $material = Material::factory()->create([
            'stock' => 20,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        $movement = $service->adjustment(
            $material,
            17,
            $user,
            'Hasil stock opname'
        );

        $this->assertEquals(17, $material->fresh()->stock);

        $this->assertEquals('ADJUSTMENT', $movement->type);
        $this->assertEquals(3, $movement->quantity);
        $this->assertEquals(20, $movement->stock_before);
        $this->assertEquals(17, $movement->stock_after);
        $this->assertEquals($user->id, $movement->user_id);
        $this->assertEquals($material->id, $movement->material_id);

        $this->assertDatabaseHas('stock_movements', [
            'id' => $movement->id,
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 3,
            'stock_before' => 20,
            'stock_after' => 17,
            'notes' => 'Hasil stock opname',
        ]);
    }

    public function test_adjustment_increases_stock_to_physical_stock_and_creates_movement(): void
    {
        $material = Material::factory()->create([
            'stock' => 20,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        $movement = $service->adjustment(
            $material,
            25,
            $user,
            'Hasil stock opname'
        );

        $this->assertEquals(25, $material->fresh()->stock);

        $this->assertEquals('ADJUSTMENT', $movement->type);
        $this->assertEquals(5, $movement->quantity);
        $this->assertEquals(20, $movement->stock_before);
        $this->assertEquals(25, $movement->stock_after);
        $this->assertEquals($user->id, $movement->user_id);
        $this->assertEquals($material->id, $movement->material_id);

        $this->assertDatabaseHas('stock_movements', [
            'id' => $movement->id,
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 5,
            'stock_before' => 20,
            'stock_after' => 25,
            'notes' => 'Hasil stock opname',
        ]);
    }

    public function test_adjustment_can_reduce_stock_to_zero(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        $movement = $service->adjustment(
            $material,
            0,
            $user,
            'Hasil stock opname'
        );

        $this->assertEquals(0, $material->fresh()->stock);

        $this->assertEquals('ADJUSTMENT', $movement->type);
        $this->assertEquals(10, $movement->quantity);
        $this->assertEquals(10, $movement->stock_before);
        $this->assertEquals(0, $movement->stock_after);

        $this->assertDatabaseHas('stock_movements', [
            'id' => $movement->id,
            'material_id' => $material->id,
            'user_id' => $user->id,
            'type' => 'ADJUSTMENT',
            'quantity' => 10,
            'stock_before' => 10,
            'stock_after' => 0,
            'notes' => 'Hasil stock opname',
        ]);
    }

    public function test_adjustment_rejects_negative_physical_stock(): void
    {
        $material = Material::factory()->create([
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        try {
            $service->adjustment(
                $material,
                -1,
                $user,
                'Hasil stock opname'
            );

            $this->fail('Expected InvalidArgumentException was not thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                'Physical stock cannot be negative.',
                $e->getMessage()
            );
        }

        $this->assertEquals(10, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_adjustment_rejects_when_physical_stock_is_same_as_current_stock(): void
    {
        $material = Material::factory()->create([
            'stock' => 20,
        ]);

        $user = User::factory()->create();

        $service = app(StockMovementService::class);

        try {
            $service->adjustment(
                $material,
                20,
                $user,
                'Hasil stock opname'
            );

            $this->fail('Expected InvalidArgumentException was not thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                'No stock adjustment is needed.',
                $e->getMessage()
            );
        }

        $this->assertEquals(20, $material->fresh()->stock);

        $this->assertDatabaseCount('stock_movements', 0);
    }
}
