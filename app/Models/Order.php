<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'product',
        'total_price'
    ];

    public function createNew(array $request)
    {
       return $this->create([
            'user_id' => auth()->id(),
            'product_variant_id' => $request['product_variant_id'],
            'quantity' => $request['quantity'],
            'status_id' => OrderStatus::waiting()->id
        ]);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getProductAttribute()
    {
        return $this->productVariant()->first()->product;
    }

    public function getTotalPriceAttribute()
    {
        return (int)$this->quantity * (int)$this->productVariant->price;
    }

}
