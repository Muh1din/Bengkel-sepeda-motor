<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Auth::user()->customer;

        $vehicles = $customer->vehicles;

        return view('customer.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'digits:4'],
            'color' => ['required', 'string', 'max:50'],
        ]);

        $customer = Auth::user()->customer;

        $customer->vehicles()->create($validated);

        return redirect()
            ->route('customer.vehicles.index')
            ->with('success', 'Berhasil menambah kendaraan.');
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
        $customer = Auth::user()->customer;

        $vehicle = $customer->vehicles()->findOrFail($id);
        return view('customer.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Auth::user()->customer;

        $vehicle = $customer->vehicles()->findOrFail($id);

        $validated = $request->validate([
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate,'. $vehicle->id],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'string', 'digits:4'],
            'color' => ['required', 'string', 'max:50'],
        ]);

        $vehicle->update($validated);

        return redirect()->route('customer.vehicles.index')->with('success', 'berhasil update kendaraan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Auth::user()->customer;

        $vehicle = $customer->vehicles()->findOrFail($id);

        $vehicle->delete();

        return redirect()
            ->route('customer.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
