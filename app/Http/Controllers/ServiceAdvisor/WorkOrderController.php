<?php

namespace App\Http\Controllers\ServiceAdvisor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleInspection;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inspections = VehicleInspection::with([
            'booking.customer',
            'booking.vehicle',
            'mechanic',
            'recommendedWorks',
            'booking.workOrder',
        ])
            ->where('status', 'COMPLETED')
            ->latest()
            ->get();

        $readyInspections = $inspections
            ->filter(function ($inspection) {
                return !$inspection->booking->workOrder
                    && !$inspection->recommendedWorks
                        ->contains('approval_status', 'PENDING');
            });

        $workOrders = WorkOrder::with([
            'booking.customer',
            'booking.vehicle',
            'mechanic',
            'workItems',
        ])
            ->latest()
            ->get();

        return view('service-advisor.work-orders.index'
                ,compact(
                'readyInspections',
                'workOrders'
            )
        );
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

    public function store(VehicleInspection $inspection)
    {
        $inspection->load('recommendedWorks');

        // Pastikan inspeksi sudah selesai
        if ($inspection->status !== 'COMPLETED') {
            return redirect()
                ->back()
                ->with('error', 'Inspeksi belum selesai.');
        }

        // Pastikan semua pekerjaan sudah memiliki keputusan customer
        $hasPendingWorks = $inspection->recommendedWorks
            ->contains('approval_status', 'PENDING');

        if ($hasPendingWorks) {
            return redirect()
                ->back()
                ->with('error', 'Masih ada pekerjaan yang belum memiliki keputusan pelanggan.');
        }

        // Pastikan WO belum pernah dibuat
        if ($inspection->booking->workOrder) {
            return redirect()
                ->back()
                ->with('error', 'Work Order untuk booking ini sudah dibuat.');
        }

        DB::transaction(function () use ($inspection) {

            $workOrder = WorkOrder::create([
                'booking_id' => $inspection->booking_id,
                'mechanic_id' => $inspection->mechanic_id,
                'work_order_code' => 'WO-' . str_pad(
                    WorkOrder::count() + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),
                'received_at' => now(),
                'status' => 'IN_PROGRESS',
            ]);

            $acceptedWorks = $inspection->recommendedWorks
                ->where('approval_status', 'ACCEPTED');

            foreach ($acceptedWorks as $work) {
                $workOrder->workItems()->create([
                    'name' => $work->name,
                    'type' => 'RECOMMENDED',
                    'description' => $work->description,
                    'status' => 'PENDING',
                ]);
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Work Order berhasil dibuat.');
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

    public function monitoringIndex()
    {
        $workOrders = WorkOrder::query()
            ->with([
                'booking.customer',
                'booking.vehicle',
                'mechanic',
            ])
            ->whereIn('status', [
                'RECEIVED',
                'INSPECTION',
                'DISASSEMBLY',
                'IN_PROGRESS',
                'ASSEMBLY',
                'FINAL_CHECK',
            ])
            ->latest()
            ->get();

        return view(
            'service-advisor.monitoring.index',
            compact('workOrders')
        );
    }

    public function monitoring(WorkOrder $workOrder)
    {
        Booking::with([
            'customer',
            'vehicle',
            'workOrder',
        ])->get();

        return view(
            'service-advisor.monitoring.show',
            compact('workOrder')
        );
    }
}
