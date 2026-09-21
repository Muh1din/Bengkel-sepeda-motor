<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, string $bookingId)
    {
        $customer = Customer::where('user_id', Auth::id())
            ->firstOrFail();

        $booking = $customer->bookings()
            ->findOrFail($bookingId);

        if ($booking->status !== 'COMPLETED') {
            return redirect()
                ->route('customer.riwayatService')
                ->with('error', 'Booking belum dapat diberikan review.');
        }

        if ($booking->review()->exists()) {
            return redirect()
                ->route('customer.riwayatService')
                ->with('error', 'Booking ini sudah memiliki review.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking->review()->create([
            'customer_id' => $customer->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()
            ->route('customer.riwayatService')
            ->with('success', 'Review berhasil diberikan.');
    }
}
