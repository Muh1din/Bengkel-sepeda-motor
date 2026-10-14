<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\RecommendedWork;
use App\Models\Booking;
use App\Models\User;

class VehicleInspection extends Model
{
    protected $fillable = [
        'booking_id',
        'mechanic_id',
        'status',
        'inspection',
        'diagnosis',
        'recommendation',
        'created_by',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id', 'id');
    }

    public function recommendedWorks(): HasMany
    {
        return $this->hasMany(RecommendedWork::class,'vehicle_inspection_id','id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
