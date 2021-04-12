<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function createNew($request)
    {
        $product = $this->create([
            'name' => $request['name']
        ]);

        $product->variants()->createMany($request['variants']);

        return $product;
    }
}
