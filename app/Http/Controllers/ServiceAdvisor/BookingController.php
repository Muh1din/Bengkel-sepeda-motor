<?php

namespace App\Http\Controllers\ServiceAdvisor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\BookingCapacityService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\VehicleInspection;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::query()
            ->with([
                'customer',
                'vehicle',
                'workOrder',
            ])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'asc')
            ->get();

        $mechanics = User::whereHas('role', function ($query) {
            $query->where('name', 'Mechanic');
        })->get();

        return view('service-advisor.booking', compact('bookings', 'mechanics'));
    }

    public function confirm(Booking $booking)
    {
        $result = DB::transaction(function () use ($booking) {
            // Lock konfigurasi kapasitas agar konfirmasi berjalan bergantian.
            $setting = DB::table('settings')
                ->where('key', 'max_booking_slots')
                ->lockForUpdate()
                ->first();

            if (!$setting) {
                return [
                    'success' => false,
                    'message' => 'Pengaturan maksimal booking belum tersedia.',
                ];
            }

            // Lock booking yang akan dikonfirmasi.
            $booking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($booking->status !== 'PENDING') {
                return [
                    'success' => false,
                    'message' => 'Booking ini tidak dapat dikonfirmasi.',
                ];
            }

            $maxSlots = (int) $setting->value;

            // Hanya CONFIRMED dan IN_PROGRESS yang menggunakan slot.
            $activeBookings = Booking::whereIn('status', [
                'CONFIRMED',
                'IN_PROGRESS',
            ])->count();

            if ($activeBookings >= $maxSlots) {
                return [
                    'success' => false,
                    'message' => 'Kapasitas booking sudah penuh. Booking tidak dapat dikonfirmasi.',
                ];
            }

            $booking->update([
                'status' => 'CONFIRMED',
            ]);

            return [
                'success' => true,
                'message' => 'Booking berhasil dikonfirmasi.',
            ];
        }, 5);

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function reject(Request $request, Booking $booking)
    {

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $booking->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Booking berhasil ditolak.');
    }

    public function receive(Request $request, Booking $booking)
    {

        // dd([
        //     'booking_id' => $booking->id,
        //     'booking_code' => $booking->booking_code,
        //     'booking_status' => $booking->status,
        //     'mechanic_id' => $request->mechanic_id,
        // ]);

        if ($booking->status !== 'CONFIRMED') {
            return redirect()
                ->back()
                ->with('error', 'Kendaraan tidak dapat diterima.');
        }

        $request->validate([
            'mechanic_id' => ['required', 'exists:users,id'],
        ]);

        $mechanic = User::find($request->mechanic_id);

        if (!$mechanic) {
            return redirect()
                ->back()
                ->with('error', 'Mekanik tidak ditemukan.');
        }

        if (!$mechanic->role || $mechanic->role->name !== 'Mechanic') {
            return redirect()
                ->back()
                ->with('error', 'User yang dipilih bukan mekanik.');
        }

        if ($mechanic->inspections()->where('status', 'IN_PROGRESS')->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Mekanik sedang memiliki inspeksi yang berjalan.');
        }

        if ($mechanic->workOrders()->where('status', 'IN_PROGRESS')->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Mekanik sedang memiliki Work Order yang berjalan.');
        }

        VehicleInspection::create([
            'booking_id' => $booking->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'IN_PROGRESS',
            'created_by' => Auth::id(),
        ]);

        $booking->update([
            'status' => 'IN_PROGRESS',
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Kendaraan berhasil diterima dan mekanik berhasil ditugaskan untuk inspeksi.'
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
