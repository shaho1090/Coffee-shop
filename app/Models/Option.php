<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function createNew(array $request)
    {
        return $this->create([
            'name' => $request['name'],
            'parent_id' => $request['parent_id'],
            'level' => $request['parent_id'] ? ((int)Option::find($request['parent_id'])->level) + 1 : 0,
        ]);
    }

    public function ProductVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function parent()
    {
        return $this->belongsTo(Option::class,'parent_id');
    }
}
