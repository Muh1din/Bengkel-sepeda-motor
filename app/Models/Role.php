<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name'];
    protected $table = 'roles';

   public function user(): HasMany {
        return $this->hasMany(User::class, 'role_id', 'id');
   }
}
