<?php

namespace App\Http\Controllers\ServiceAdvisor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // dd([
        //     'today' => today()->toDateString(),
        //     'booking_dates' => Booking::pluck('booking_date')->toArray(),
        //     'booking_today' => Booking::whereDate('booking_date', today())->count(),
        // ]);

        $pendingBooking = Booking::query()
            ->where('status', 'PENDING')
            ->count();

        $bookingToday = Booking::whereDate('booking_date', today())
            ->count();

        $bookings = Booking::query()
            ->with(['customer', 'vehicle'])
            ->whereDate('booking_date', today())
            ->orderBy('booking_time')
            ->get();

        $mechanics = User::whereHas('role', function ($query) {
            $query->where('name', 'Mechanic');
        })->get();


        $activeWorkOrders = WorkOrder::query()->where('status', 'IN_PROGRESS')->count();

        $activeServicesList = Booking::query()->with(['vehicle', 'workOrder.mechanic', 'workOrder.workItems',])->where('status', 'IN_PROGRESS')->get();

        return view(
            'service-advisor.dashboard',
            compact(
                'bookings',
                'pendingBooking',
                'bookingToday',
                'activeServicesList',
                'activeWorkOrders',
                'mechanics'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
