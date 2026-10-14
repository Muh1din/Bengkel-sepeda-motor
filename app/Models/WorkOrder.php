<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'booking_id',
        'mechanic_id',
        'work_order_code',
        'received_at',
        'status',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id', 'id');
    }

    public function workItems(): HasMany
    {
        return $this->hasMany(WorkItem::class, 'work_order_id', 'id');
    }

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
