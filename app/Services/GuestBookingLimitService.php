<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GuestBookingLimitService
{
    private const MAX_ACTIVE_BOOKINGS = 3;

    private const ACTIVE_STATUSES = [
        'PENDING',
        'CONFIRMED',
        'IN_PROGRESS',
    ];

    public function execute(string $ipAddress, callable $callback): mixed
    {
        $lockKey = 'guest-booking:' . hash('sha256', $ipAddress);

        return Cache::lock($lockKey, 60)->block(10, function () use ($ipAddress, $callback) {
            return DB::transaction(function () use ($ipAddress, $callback) {

                $activeBookings = Booking::query()
                    ->where('ip_address', $ipAddress)
                    ->whereIn('status', self::ACTIVE_STATUSES)
                    ->count();

                if ($activeBookings >= self::MAX_ACTIVE_BOOKINGS) {
                    throw ValidationException::withMessages([
                        'booking' => 'Anda sudah mencapai batas maksimal 3 booking aktif.',
                    ]);
                }

                return $callback();
            });
        });
    }
}
