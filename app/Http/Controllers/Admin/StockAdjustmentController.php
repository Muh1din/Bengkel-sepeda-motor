<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Services\StockMovementService;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function __construct(private StockMovementService $stockMovementService) {}

    public function index()
    {
        $materials = Material::orderBy('name')->get();

        return view('admin.stock-adjustments.index', compact(
            'materials'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => ['required', 'exists:materials,id'],
            'physical_stock' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $material = Material::findOrFail(
            $validated['material_id']
        );

        $this->stockMovementService->adjustment(
            material: $material,
            physicalStock: $validated['physical_stock'],
            user: $request->user(),
            notes: $validated['notes'] ?? null
        );

        return redirect()
            ->route('admin.stock-adjustments.index')
            ->with('success', 'Stok berhasil disesuaikan.');
    }
}
