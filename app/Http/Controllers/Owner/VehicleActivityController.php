<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class VehicleActivityController extends Controller
{
    public function index()
    {
        $vehicleActivities = Booking::with([
            'customer',
            'vehicle',
            'inspection.mechanic',
            'workOrder.mechanic',
        ])
            ->latest('created_at')
            ->paginate(10);

        return view('owner.vehicle-activities.index', compact(
            'vehicleActivities'
        ));
    }
}
