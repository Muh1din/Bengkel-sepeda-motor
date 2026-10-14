<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{

    protected $fillable = [
        'project_category_id',
        'title',
        'slug',
        'vehicle_name',
        'summary',
        'description',
        'status',
        'completed_at',
    ];
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class, 'project_id');
    }

    protected function casts(): array
    {
        return [
            'completed_at' => 'date',
        ];
    }
}
