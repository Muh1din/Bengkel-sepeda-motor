<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ItemUsageReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date']
            ?? Carbon::today()->startOfMonth()->toDateString();

        $endDate = $validated['end_date']
            ?? Carbon::today()->toDateString();

        $query = StockMovement::with([
            'material.category',
            'user',
            'workOrder',
        ])
            ->where('type', 'OUT')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $statistics = [
            'total_transactions' => (clone $query)->count(),
            'total_quantity' => (clone $query)->sum('quantity'),
            'total_materials' => (clone $query)
                ->distinct('material_id')
                ->count('material_id'),
            'total_work_orders' => (clone $query)
                ->whereNotNull('work_order_id')
                ->distinct('work_order_id')
                ->count('work_order_id'),
        ];

        $itemUsage = (clone $query)
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('owner.reports.item-usage', compact(
            'statistics',
            'itemUsage',
            'startDate',
            'endDate',
        ));
    }
}
