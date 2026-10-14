<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\BookingCapacityService;
use App\Models\Review;
use App\Models\Project;
use App\Models\Booking;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BookingCapacityService $capacityService)
    {
        $booking = null;

        $bookingId = session()->get('tracking_booking_id');

        if ($bookingId) {
            $booking = Booking::with([
                'vehicle',
                'inspection.recommendedWorks',
                'workOrder.workItems',
            ])->find($bookingId);
        }

        $projects = Project::with(['images', 'category'])
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();

        $reviews = Review::with('customer')
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        // Informasi kapasitas booking
        $maxBookingSlots = $capacityService->getMaxSlots();
        $remainingBookingSlots = $capacityService->getRemainingSlots();
        $activeVehicle = $capacityService->getActiveVehicle();

        return view('app.index', compact(
            'booking',
            'projects',
            'reviews',
            'maxBookingSlots',
            'remainingBookingSlots',
            'activeVehicle'
        ));
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
