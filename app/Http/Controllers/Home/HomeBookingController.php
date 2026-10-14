<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;

class HomeBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('components.home.booking.index');
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
        // 1. Normalize user input
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'phone' => trim((string) $request->input('phone')),
            'license_plate' => strtoupper(
                trim((string) $request->input('license_plate'))
            ),
        ]);

        // 2. Validate booking form
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],

            'license_plate' => ['required', 'string', 'max:20'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => [
                'required',
                'integer',
                'min:1901',
                'max:' . now()->year,
            ],
            'color' => ['required', 'string', 'max:50'],

            'service_type' => [
                'required',
                'in:Restorasi Full,Repaint,Bore Up Harian,Upgrade Kirian (CVT),Service & Detailing,Lainnya',
            ],

            'custom_service' => [
                'required_if:service_type,Lainnya',
                'nullable',
                'string',
                'max:100',
            ],

            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'booking_time' => ['required', 'date_format:H:i'],

            'complaint' => ['nullable', 'string', 'max:5000'],
        ]);

        // 3. Determine the final service type
        $serviceType = $validated['service_type'] === 'Lainnya'
            ? trim($validated['custom_service'])
            : $validated['service_type'];

        // 4. Save customer, vehicle, and booking atomically
        $booking = DB::transaction(function () use ($validated, $serviceType) {

            // Find customer by phone number
            $customer = Customer::where(
                'phone',
                $validated['phone']
            )->first();

            if ($customer && $customer->user_id !== null) {
                throw ValidationException::withMessages([
                    'phone' => 'Nomor telepon ini terhubung dengan akun pelanggan. Silakan login untuk melakukan booking.',
                ]);
            }

            // Create a guest customer if one does not exist
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'email' => null,
                    'address' => null,
                    'user_id' => null,
                ]);
            }

            // Find vehicle by license plate
            $vehicle = Vehicle::where(
                'license_plate',
                $validated['license_plate']
            )->first();

            if ($vehicle && $vehicle->customer_id !== $customer->id) {
                throw ValidationException::withMessages([
                    'license_plate' => 'Nomor polisi ini sudah terdaftar pada customer lain. Periksa kembali nomor polisi kendaraan.',
                ]);
            }

            // Create a vehicle if it does not exist
            if (!$vehicle) {
                $vehicle = $customer->vehicles()->create([
                    'license_plate' => $validated['license_plate'],
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'year' => $validated['year'],
                    'color' => $validated['color'],
                ]);
            }

            // Generate a unique booking code
            do {
                $bookingCode = 'BK-' . str_pad(
                    (string) random_int(0, 999999),
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            } while (
                Booking::where('booking_code', $bookingCode)->exists()
            );

            // Create the booking
            return Booking::create([
                'booking_code' => $bookingCode,
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'service_type' => $serviceType,
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'],
                'complaint' => $validated['complaint'] ?? null,
                'status' => 'PENDING',
            ]);
        });

        // 5. Return the booking confirmation
        return redirect()
            ->route('booking.form.index')
            ->with('success', 'Booking berhasil dibuat!')
            ->with('booking_code', $booking->booking_code);
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
