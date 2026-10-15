<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Rule;
use App\Models\MaterialCategory;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::with('category')->latest()->get();

        return view('admin.materials.index', compact('materials'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MaterialCategory::all();

        return view('admin.materials.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'material_category_id' => [
                'required',
                'exists:material_categories,id',
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255',
                'unique:materials,barcode',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $validated['stock'] = 0;

        Material::create($validated);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Material berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $categories = MaterialCategory::orderBy('name')->get();
        return view('admin.materials.edit', compact('material', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'category_id' => [
                'required',
                'exists:material_categories,id',
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255',
                'unique:materials,barcode,' . $material->id,
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $material->update($validated);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Material berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('admin.materials.index')->with('success', 'Material berhasil dihapus.');
    }
}
