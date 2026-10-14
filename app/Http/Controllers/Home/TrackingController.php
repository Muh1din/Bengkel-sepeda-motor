<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function search(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
            [
                'booking_code' => [
                    'required',
                    'string',
                    'regex:/^BK-[A-Z0-9]{6}$/i',
                ],
            ],
            [
                'booking_code.required' => 'Kode booking wajib diisi.',
                'booking_code.regex' => 'Format kode booking tidak valid.',
            ]
        );

        // Jika format kode booking tidak valid.
        if ($validator->fails()) {
            return redirect()
                ->to(url('/#tracking'))
                ->withInput()
                ->withErrors($validator)
                ->with('tracking_section', true);
        }

        $bookingCode = strtoupper(
            trim($validator->validated()['booking_code'])
        );

        $booking = Booking::query()
            ->where('booking_code', $bookingCode)
            ->with([
                'vehicle',
                'inspection.recommendedWorks',
                'workOrder.workItems',
            ])
            ->first();

        // Jika kode booking tidak ditemukan.
        if (!$booking) {
            return redirect()
                ->to(url('/#tracking'))
                ->withInput()
                ->withErrors([
                    'booking_code' => 'Booking tidak ditemukan. Periksa kembali kode booking Anda.',
                ])
                ->with('tracking_section', true);
        }

        // Jika kode booking ditemukan.
        return redirect()
            ->to(url('/#tracking'))
            ->with('tracking_booking_id', $booking->id)
            ->with('tracking_section', true);
    }
}
