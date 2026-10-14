<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkItem extends Model
{
    protected $fillable = [
        'work_order_id',
        'name',
        'type',
        'description',
        'status',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id', 'id');
    }
}
