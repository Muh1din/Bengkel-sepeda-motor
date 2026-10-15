<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with([
            'material',
            'user',
            'workOrder',
        ]);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $stockMovements = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.stock-movements.index', compact(
            'stockMovements'
        ));
    }
}
