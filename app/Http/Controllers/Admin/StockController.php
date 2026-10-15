<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;

class StockController extends Controller
{
    public function index()
    {
        $materials = Material::with('category')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.stock.index', compact('materials'));
    }
}
