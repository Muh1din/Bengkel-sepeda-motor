<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialRequest;

class MaterialRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialRequest::with([
            'workOrder',
            'mechanic',
            'items.material',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $materialRequests = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.material-requests.index', compact(
            'materialRequests'
        ));
    }
}
