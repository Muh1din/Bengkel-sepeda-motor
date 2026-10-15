<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;

class WorkOrderController extends Controller
{
    public function index()
    {
        $statistics = [
            'total' => WorkOrder::count(),

            'active' => WorkOrder::whereNotNull('received_at')
                ->whereNull('completed_at')
                ->count(),

            'completed' => WorkOrder::whereNotNull('completed_at')
                ->count(),
        ];

        $workOrders = WorkOrder::with([
            'booking.vehicle',
            'mechanic',
        ])
            ->latest('id')
            ->paginate(10);

        return view('owner.work-orders.index', compact(
            'statistics',
            'workOrders'
        ));
    }
}
