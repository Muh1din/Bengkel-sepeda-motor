<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_category_id',
        'name',
        'barcode',
        'unit',
        'stock',
        'minimum_stock',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    
}
