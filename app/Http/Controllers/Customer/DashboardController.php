<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        $totalVehicles = $customer->vehicles()->count();
        $activeBookings = $customer->bookings()->whereIn('status', ['PENDING', 'CONFIRMED', 'IN_PROGRESS',])->count();
        $runningServices = $customer->bookings()->where('status', 'IN_PROGRESS')->count();
        $serviceHistory = $customer->bookings()->where('status', 'COMPLETED')->count();
        $latestBookings = $customer->bookings()->latest()->take(3)->get();
        $currentService = $customer->bookings()->where('status', 'IN_PROGRESS')->with('vehicle')->latest()->first();
        return view('customer.dashboard', compact('customer', 'totalVehicles', 'activeBookings', 'runningServices', 'serviceHistory', 'latestBookings', 'currentService'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

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
