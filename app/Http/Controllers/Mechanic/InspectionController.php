<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\VehicleInspection;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inspections = VehicleInspection::with([
            'booking.customer',
            'booking.vehicle',
        ])
            ->where('mechanic_id', Auth::id())
            ->where('status', 'IN_PROGRESS')
            ->latest()
            ->get();

        return view('mechanic.inspections.index', compact('inspections'));
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
    public function show(VehicleInspection $inspection)
    {
        if ($inspection->mechanic_id !== Auth::id()) {
            abort(403);
        }

        $inspection->load([
            'booking.customer',
            'booking.vehicle',
            'recommendedWorks',
        ]);

        return view('mechanic.inspections.show', compact('inspection'));
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

    public function saveDraft(Request $request, VehicleInspection $inspection)
    {
        if ($inspection->mechanic_id !== Auth::id()) {
            abort(403);
        }

        if ($inspection->status !== 'IN_PROGRESS') {
            return redirect()
                ->route('mechanic.inspections.show', $inspection)
                ->with('error', 'Inspeksi ini sudah diselesaikan.');
        }

        $validated = $request->validate([
            'inspection' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
            'recommendation' => ['nullable', 'string'],
            'works' => ['nullable', 'array'],
            'works.*.name' => ['required', 'string', 'max:255'],
            'works.*.description' => ['nullable', 'string'],
            'works.*.estimated_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $inspection->update([
            'inspection' => $validated['inspection'] ?? null,
            'diagnosis' => $validated['diagnosis'] ?? null,
            'recommendation' => $validated['recommendation'] ?? null,
        ]);

        $inspection->recommendedWorks()->delete();

        foreach ($validated['works'] ?? [] as $work) {
            $inspection->recommendedWorks()->create([
                'name' => $work['name'],
                'description' => $work['description'] ?? null,
                'estimated_cost' => $work['estimated_cost'] ?? null,
                'approval_status' => 'PENDING',
            ]);
        }

        return redirect()
            ->route('mechanic.inspections.show', $inspection)
            ->with('success', 'Draft inspeksi berhasil disimpan.');
    }


    public function complete(Request $request, VehicleInspection $inspection)
    {
        if ($inspection->mechanic_id !== Auth::id()) {
            abort(403);
        }

        if ($inspection->status !== 'IN_PROGRESS') {
            return redirect()
                ->route('mechanic.inspections.show', $inspection)
                ->with('error', 'Inspeksi ini sudah diselesaikan.');
        }

        $validated = $request->validate([
            'inspection' => ['required', 'string'],
            'diagnosis' => ['required', 'string'],
            'recommendation' => ['nullable', 'string'],
            'works' => ['nullable', 'array'],
            'works.*.name' => ['required', 'string', 'max:255'],
            'works.*.description' => ['nullable', 'string'],
            'works.*.estimated_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $inspection->update([
            'inspection' => $validated['inspection'],
            'diagnosis' => $validated['diagnosis'],
            'recommendation' => $validated['recommendation'] ?? null,
            'status' => 'COMPLETED',
        ]);

        $inspection->recommendedWorks()->delete();

        foreach ($validated['works'] ?? [] as $work) {
            $inspection->recommendedWorks()->create([
                'name' => $work['name'],
                'description' => $work['description'] ?? null,
                'estimated_cost' => $work['estimated_cost'] ?? null,
                'approval_status' => 'PENDING',
            ]);
        }

        return redirect()
            ->route('mechanic.inspections.index')
            ->with('success', 'Inspeksi berhasil diselesaikan dan dikirim ke Service Advisor.');
    }
}
