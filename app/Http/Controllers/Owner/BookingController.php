<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
            'customer',
            'vehicle',
        ])
            ->latest('created_at')
            ->paginate(10);

        return view('owner.bookings.index', compact('bookings'));
    }
}
