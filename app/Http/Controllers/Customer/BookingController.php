<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Customer::where('user_id', Auth::id())
            ->firstOrFail();

        $bookings = $customer->bookings()
            ->whereIn('status', [
                'PENDING',
                'CONFIRMED',
                'IN_PROGRESS',
            ])
            ->latest()
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customer = Customer::where('user_id', Auth::id())->firstOrFail();

        $vehicles = $customer->vehicles;

        return view('customer.bookings.create', compact('vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customer = Customer::where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_type' => ['required', 'string', 'max:255'],
            'booking_date' => ['required', 'date'],
            'booking_time' => ['required'],
            'complaint' => ['nullable', 'string'],
        ]);

        $vehicle = $customer->vehicles()
            ->findOrFail($validated['vehicle_id']);

        $customer->bookings()->create([
            'booking_code' => 'BK-' . strtoupper(fake()->unique()->numerify('######')),
            'vehicle_id' => $vehicle->id,
            'service_type' => $validated['service_type'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'complaint' => $validated['complaint'] ?? null,
            'status' => 'PENDING',
        ]);

        return redirect()
            ->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibuat.');
    }

    public function cancel(Request $request, string $id)
    {
        $customer = Customer::where('user_id', Auth::id())
            ->firstOrFail();

        $booking = $customer->bookings()
            ->findOrFail($id);

        if ($booking->status !== 'PENDING') {
            return redirect()
                ->route('customer.bookings.index')
                ->with('error', 'Booking tidak dapat dibatalkan.');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $booking->update([
            'status' => 'CANCELLED',
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        return redirect()
            ->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    public function tracking()
    {
        $customer = Customer::where('user_id', Auth::id())->firstOrFail();
        $currentService = $customer->bookings()->whereIn('status', ['CONFIRMED', 'IN_PROGRESS', 'COMPLETED',])->with('vehicle')->latest()->first();
        return view('customer.trackingService', compact('currentService'));
    }

    public function history()
    {
        $customer = Customer::where('user_id', Auth::id())
            ->firstOrFail();

        $serviceHistory = $customer->bookings()
            ->where('status', 'COMPLETED')
            ->with('vehicle', 'review')
            ->latest('booking_date')
            ->get();

        return view(
            'customer.riwayatService',
            compact('serviceHistory')
        );
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
