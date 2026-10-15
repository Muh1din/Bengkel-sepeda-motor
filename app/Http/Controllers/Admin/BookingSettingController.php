<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use App\Services\BookingCapacityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingSettingController extends Controller
{
    /**
     * Menampilkan pengaturan slot booking.
     */
    public function edit(
        BookingCapacityService $capacityService
    ): View {
        $maxBookingSlots = $capacityService->getMaxSlots();
        $usedBookingSlots = $capacityService->getActiveBookingsCount();
        $remainingBookingSlots = $capacityService->getRemainingSlots();
        $activeVehicle = $capacityService->getActiveVehicle();

        return view('admin.booking-settings.edit', [
            'maxBookingSlots' => $maxBookingSlots,
            'usedBookingSlots' => $usedBookingSlots,
            'remainingBookingSlots' => $remainingBookingSlots,
            'activeVehicle' => $activeVehicle,
        ]);
    }

    /**
     * Memperbarui kapasitas maksimal booking.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'max_booking_slots' => [
                'required',
                'integer',
                'min:1',
                'max:10000',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $setting = Setting::where(
                'key',
                'max_booking_slots'
            )->lockForUpdate()->first();

            if (!$setting) {
                throw ValidationException::withMessages([
                    'max_booking_slots' =>
                    'Pengaturan kapasitas booking belum tersedia.',
                ]);
            }

            $usedBookingSlots = Booking::whereIn('status', [
                'CONFIRMED',
                'IN_PROGRESS',
            ])->count();

            $newCapacity = (int) $validated['max_booking_slots'];

            if ($newCapacity < $usedBookingSlots) {
                throw ValidationException::withMessages([
                    'max_booking_slots' =>
                    "Kapasitas tidak boleh kurang dari {$usedBookingSlots} slot yang sedang terpakai.",
                ]);
            }

            $setting->update([
                'value' => (string) $newCapacity,
            ]);
        }, 5);

        return back()->with(
            'success',
            'Maksimal slot booking berhasil diperbarui.'
        );
    }
}
