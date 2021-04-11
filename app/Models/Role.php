<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public function scopeManager($query)
    {
        return $query->where('title', 'manager');
    }

    public function scopeCustomer($query)
    {
        return $query->where('title', 'customer');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
