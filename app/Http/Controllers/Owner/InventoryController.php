<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\StockMovement;

class InventoryController extends Controller
{
    public function index()
    {
        $statistics = [
            'total_materials' => Material::count(),

            'low_stock_materials' => Material::whereColumn(
                'stock',
                '<=',
                'minimum_stock'
            )->where('stock', '>', 0)->count(),

            'out_of_stock_materials' => Material::where(
                'stock',
                0
            )->count(),

            'total_stock_movements' => StockMovement::count(),
        ];

        $materials = Material::with('category')
            ->orderBy('name')
            ->paginate(10);

        $recentMovements = StockMovement::with([
            'material',
            'user',
        ])
            ->latest()
            ->take(10)
            ->get();

        return view('owner.inventory.index', compact(
            'statistics',
            'materials',
            'recentMovements'
        ));
    }
}
