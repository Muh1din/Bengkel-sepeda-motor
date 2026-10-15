<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialRequest;
use Illuminate\Http\Request;

class RequestRejectionController extends Controller
{
    public function store(
        Request $request,
        MaterialRequest $materialRequest
    ) {
        if ($materialRequest->status !== 'PENDING') {
            abort(422, 'Material request cannot be rejected.');
        }

        $materialRequest->update([
            'status' => 'REJECTED',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Material request rejected successfully.');
    }
}
