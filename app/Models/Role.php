<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public function scopeCustomer($query)
    {
        return $query->where('title', 'customer');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
