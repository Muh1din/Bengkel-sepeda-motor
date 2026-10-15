<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\WorkItem;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MechanicWorkOrderController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with([
            'booking.customer',
            'booking.vehicle',
            'workItems',
        ])
            ->where('mechanic_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'mechanic.work-orders.index',
            compact('workOrders')
        );
    }

    public function show(WorkOrder $workOrder)
    {
        if ($workOrder->mechanic_id !== Auth::id()) {
            abort(403);
        }

        $workOrder->load([
            'booking.customer',
            'booking.vehicle',
            'workItems',
        ]);

        return view(
            'mechanic.work-orders.show',
            compact('workOrder')
        );
    }

    public function start(WorkItem $workItem)
    {
        $workOrder = $workItem->workOrder;

        if ($workOrder->mechanic_id !== Auth::id()) {
            abort(403);
        }

        if ($workItem->status !== 'PENDING') {
            return redirect()
                ->back()
                ->with('error', 'Pekerjaan tidak dapat dimulai.');
        }

        $workItem->update([
            'status' => 'IN_PROGRESS',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pekerjaan berhasil dimulai.');
    }

    public function complete(WorkItem $workItem)
    {
        $workOrder = $workItem->workOrder;

        if ($workOrder->mechanic_id !== Auth::id()) {
            abort(403);
        }

        if ($workItem->status !== 'IN_PROGRESS') {
            return redirect()
                ->back()
                ->with('error', 'Pekerjaan belum sedang dikerjakan.');
        }

        DB::transaction(function () use ($workItem, $workOrder) {

            $workItem->update([
                'status' => 'COMPLETED',
            ]);

            $workOrder->load('workItems');

            $allCompleted = $workOrder->workItems
                ->every(fn($item) => $item->status === 'COMPLETED');

            if ($allCompleted) {
                $workOrder->update([
                    'status' => 'COMPLETED',
                    'completed_at' => now(),
                ]);

                $workOrder->booking->update([
                    'status' => 'COMPLETED',
                ]);
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Pekerjaan berhasil diselesaikan.');
    }
}
