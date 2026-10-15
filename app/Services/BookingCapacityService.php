<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Setting;

class BookingCapacityService
{
    /**
     * Status booking yang menggunakan slot.
     */
    private const ACTIVE_STATUSES = [
        'CONFIRMED',
        'IN_PROGRESS',
    ];

    /**
     * Mengambil kapasitas maksimal booking.
     */
    public function getMaxSlots(): int
    {
        return (int) Setting::where('key', 'max_booking_slots')
            ->value('value');
    }

    /**
     * Menghitung jumlah booking aktif.
     */
    public function getActiveBookingsCount(): int
    {
        return Booking::whereIn('status', self::ACTIVE_STATUSES)
            ->count();
    }

    /**
     * Menghitung sisa slot booking.
     */
    public function getRemainingSlots(): int
    {
        return max(
            0,
            $this->getMaxSlots() - $this->getActiveBookingsCount()
        );
    }

    /**
     * Mengambil satu kendaraan yang sedang dikerjakan.
     */
    public function getActiveVehicle(): ?Booking
    {
        return Booking::with('vehicle')
            ->where('status', 'IN_PROGRESS')
            ->latest()
            ->first();
    }
}
