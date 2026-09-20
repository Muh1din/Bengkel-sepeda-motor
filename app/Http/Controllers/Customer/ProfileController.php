<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Auth::user()->customer;

        return view('customer.profile.index', compact('customer'));
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
    public function edit()
    {
        $customer = Auth::user()->customer;
        return view('customer.profile.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $customer = Auth::user()->customer;
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:customers,email,' . $customer->id,
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:customers,phone,' . $customer->id,
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        DB::transaction(function () use ($customer, $user, $validated) {

            $customer->update($validated);

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);
        });

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
