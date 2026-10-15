<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $materials = Material::with('category')
            ->orderBy('name')
            ->get();

        $stockIns = StockMovement::with('material')
            ->where('type', 'IN')
            ->latest()
            ->paginate(10);

        $totalTransactions = StockMovement::where('type', 'IN')->count();

        $totalItemsThisMonth = StockMovement::where('type', 'IN')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        return view('admin.stock-in.index', compact(
            'materials',
            'stockIns',
            'totalTransactions',
            'totalItemsThisMonth'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => ['required', 'exists:materials,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated) {
            $material = Material::lockForUpdate()
                ->findOrFail($validated['material_id']);

            $stockBefore = $material->stock;
            $stockAfter = $stockBefore + $validated['quantity'];

            $material->update([
                'stock' => $stockAfter,
            ]);

            StockMovement::create([
                'material_id' => $material->id,
                'user_id' => Auth::id(),
                'type' => 'IN',
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.stock-in.index')
            ->with('success', 'Stock In berhasil diproses.');
    }
}
