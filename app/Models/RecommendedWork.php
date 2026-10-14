<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendedWork extends Model
{
    protected $fillable = [
        'vehicle_inspection_id',
        'name',
        'description',
        'estimated_cost',
        'approval_status',
    ];

    public function vehicleInspection(): BelongsTo
    {
        return $this->belongsTo(VehicleInspection::class,'vehicle_inspection_id','id');
    }
}
