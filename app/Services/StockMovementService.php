<?php

namespace App\Services;

use App\Models\Material;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StockMovementService
{
    public function stockIn(
        Material $material,
        int $quantity,
        User $user,
        ?string $notes = null
    ): StockMovement {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException(
                'Stock in quantity must be greater than zero.'
            );
        }

        return DB::transaction(function () use (
            $material,
            $quantity,
            $user,
            $notes
        ) {
            $stockBefore = $material->stock;
            $stockAfter = $stockBefore + $quantity;

            $material->stock = $stockAfter;
            $material->save();

            return StockMovement::create([
                'material_id' => $material->id,
                'user_id' => $user->id,
                'type' => 'IN',
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'notes' => $notes,
            ]);
        });
    }

    public function stockOut(
        Material $material,
        int $quantity,
        User $user,
        ?int $workOrderId = null,
        ?string $notes = null
    ): StockMovement {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException(
                'Stock out quantity must be greater than zero.'
            );
        }

        if ($quantity > $material->stock) {
            throw new \InvalidArgumentException(
                'Insufficient stock.'
            );
        }

        return DB::transaction(function () use (
            $material,
            $quantity,
            $user,
            $workOrderId,
            $notes
        ) {
            $stockBefore = $material->stock;
            $stockAfter = $stockBefore - $quantity;

            $material->stock = $stockAfter;
            $material->save();

            return StockMovement::create([
                'material_id' => $material->id,
                'work_order_id' => $workOrderId,
                'user_id' => $user->id,
                'type' => 'OUT',
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'notes' => $notes,
            ]);
        });
    }

    public function adjustment(
        Material $material,
        int $physicalStock,
        User $user,
        ?string $notes = null
    ): StockMovement {
        if ($physicalStock < 0) {
            throw new \InvalidArgumentException(
                'Physical stock cannot be negative.'
            );
        }

        if ($physicalStock === $material->stock) {
            throw new \InvalidArgumentException(
                'No stock adjustment is needed.'
            );
        }

        return DB::transaction(function () use (
            $material,
            $physicalStock,
            $user,
            $notes
        ) {
            $stockBefore = $material->stock;
            $stockAfter = $physicalStock;
            $quantity = abs($stockAfter - $stockBefore);

            $material->stock = $stockAfter;
            $material->save();

            return StockMovement::create([
                'material_id' => $material->id,
                'user_id' => $user->id,
                'type' => 'ADJUSTMENT',
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'notes' => $notes,
            ]);
        });
    }
}