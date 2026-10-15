<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = MaterialCategory::orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:material_categories,name',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ]);

        MaterialCategory::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(MaterialCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, MaterialCategory $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:material_categories,name,' . $category->id,
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ]);

        // dd([
        //     'category_id' => $category->id,
        //     'before' => $category->toArray(),
        //     'validated' => $validated,
        // ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(MaterialCategory $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
