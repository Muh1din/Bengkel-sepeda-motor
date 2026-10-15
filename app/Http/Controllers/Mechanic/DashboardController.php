<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\VehicleInspection;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mechanicId = Auth::id();

        $assignedInspections = VehicleInspection::query()
            ->where('mechanic_id', $mechanicId)
            ->count();

        $pendingInspections = VehicleInspection::query()
            ->where('mechanic_id', $mechanicId)
            ->where('status', 'IN_PROGRESS')
            ->count();

        $assignedWorkOrders = WorkOrder::where('mechanic_id', Auth::id())
            ->whereNot('status', 'COMPLETED')
            ->count();

        $inProgressWorkOrders = WorkOrder::query()
            ->where('mechanic_id', $mechanicId)
            ->where('status', 'IN_PROGRESS')
            ->count();

        $completedToday = WorkOrder::query()
            ->where('mechanic_id', $mechanicId)
            ->where('status', 'COMPLETED')
            ->whereDate('completed_at', today())
            ->count();

        $recentWorkOrders = WorkOrder::query()
            ->with([
                'booking.vehicle',
            ])
            ->where('mechanic_id', $mechanicId)
            ->latest()
            ->take(5)
            ->get();

        return view(
            'mechanic.dashboard',
            compact(
                'assignedInspections',
                'pendingInspections',
                'assignedWorkOrders',
                'inProgressWorkOrders',
                'completedToday',
                'recentWorkOrders'
            )
        );
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
