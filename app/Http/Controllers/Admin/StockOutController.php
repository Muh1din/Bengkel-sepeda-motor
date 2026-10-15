<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialRequest;
use App\Models\StockMovement;

class StockOutController extends Controller
{
    public function index()
    {
        $materialRequests = MaterialRequest::with([
            'workOrder',
            'mechanic',
            'items.material',
        ])
            ->where('status', 'PENDING')
            ->latest()
            ->get();

        $stockOuts = StockMovement::with([
            'material',
            'workOrder',
            'user',
        ])
            ->where('type', 'OUT')
            ->latest()
            ->paginate(10);

        $totalTransactions = StockMovement::where('type', 'OUT')
            ->count();

        $totalItemsThisMonth = StockMovement::where('type', 'OUT')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        return view('admin.stock-out.index', compact(
            'materialRequests',
            'stockOuts',
            'totalTransactions',
            'totalItemsThisMonth'
        ));
    }
}
