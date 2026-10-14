<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRequestItem extends Model
{
    Use HasFactory;
    
    protected $fillable = [
        'material_request_id',
        'material_id',
        'quantity',
    ];

    public function materialRequest(): BelongsTo
    {
        return $this->belongsTo(
            MaterialRequest::class,
            'material_request_id'
        );
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
