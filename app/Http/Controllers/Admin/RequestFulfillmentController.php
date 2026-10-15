<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\StockMovementService;
use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestFulfillmentController extends Controller
{
    public function store(
        Request $request,
        MaterialRequest $materialRequest,
        StockMovementService $stockMovementService
    ) {
        DB::transaction(function () use (
            $materialRequest,
            $stockMovementService
        ) {
            if ($materialRequest->status !== 'PENDING') {
                abort(422, 'Material request cannot be fulfilled.');
            }

            foreach ($materialRequest->items as $item) {
                $stockMovementService->stockOut(
                    $item->material,
                    $item->quantity,
                    Auth::user(),
                    $materialRequest->work_order_id,
                    'Material request #' . $materialRequest->id
                );
            }

            $materialRequest->update([
                'status' => 'FULFILLED',
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Material request fulfilled successfully.');
    }
}
