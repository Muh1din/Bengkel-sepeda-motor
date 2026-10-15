<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestItemController extends Controller
{
    public function index(Request $request)
    {
        $workOrder = WorkOrder::with([
            'booking.customer',
            'booking.vehicle',
        ])->findOrFail($request->work_order_id);

        abort_unless(
            $workOrder->mechanic_id === Auth::id(),
            403
        );

        $materials = Material::orderBy('name')->get();

        return view('mechanic.request-item.index', compact(
            'workOrder',
            'materials'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_order_id' => ['required', 'exists:work_orders,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:materials,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $workOrder = WorkOrder::findOrFail(
            $validated['work_order_id']
        );

        abort_unless(
            $workOrder->mechanic_id === Auth::id(),
            403
        );

        DB::transaction(function () use ($validated, $workOrder) {
            $materialRequest = MaterialRequest::create([
                'work_order_id' => $workOrder->id,
                'mechanic_id' => Auth::id(),
                'status' => 'PENDING',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $materialRequest->items()->create([
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('mechanic.work-orders.index', [
                'work_order_id' => $workOrder->id,
            ])
            ->with('success', 'Material request created successfully.');
    }
}
