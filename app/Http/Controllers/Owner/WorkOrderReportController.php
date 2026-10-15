<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkOrderReportController extends Controller
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

        $query = WorkOrder::with([
            'booking.vehicle',
            'booking.customer',
            'mechanic',
        ])
            ->whereDate('created_at', '<=', $endDate)
            ->whereDate('created_at', '>=', $startDate);

        $statistics = [
            'total' => (clone $query)->count(),

            'active' => (clone $query)
                ->whereNotNull('received_at')
                ->whereNull('completed_at')
                ->count(),

            'completed' => (clone $query)
                ->whereNotNull('completed_at')
                ->count(),

            'not_received' => (clone $query)
                ->whereNull('received_at')
                ->count(),
        ];

        $statusData = (clone $query)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $workOrders = (clone $query)
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('owner.reports.work-orders', compact(
            'statistics',
            'statusData',
            'workOrders',
            'startDate',
            'endDate'
        ));
    }
}
