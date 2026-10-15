<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\StockMovement;

class InventoryDashboardController extends Controller
{
    public function index()
    {
        $totalMaterials = Material::count();

        $lowStockMaterials = Material::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )
            ->orderBy('stock')
            ->get();

        $pendingRequests = MaterialRequest::where(
            'status',
            'PENDING'
        )->count();

        $stockInThisMonth = StockMovement::where(
            'type',
            'IN'
        )
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        $stockOutThisMonth = StockMovement::where(
            'type',
            'OUT'
        )
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        $recentMovements = StockMovement::with([
            'material',
            'user',
            'workOrder',
        ])
            ->joinSub(
                StockMovement::query()
                    ->select('id')
                    ->latest()
                    ->limit(20),
                'recent_movements',
                function ($join) {
                    $join->on(
                        'stock_movements.id',
                        '=',
                        'recent_movements.id'
                    );
                }
            )
            ->select('stock_movements.*')
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $recentRequests = MaterialRequest::with([
            'workOrder',
            'mechanic',
            'items.material',
        ])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMaterials',
            'lowStockMaterials',
            'pendingRequests',
            'stockInThisMonth',
            'stockOutThisMonth',
            'recentMovements',
            'recentRequests'
        ));
    }
}
