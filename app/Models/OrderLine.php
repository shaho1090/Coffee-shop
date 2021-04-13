<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'product',
        'line_total_price'
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getProductAttribute()
    {
        return $this->productVariant()->first()->product;
    }

    public function header()
    {
        return $this->belongsTo(OrderHeader::class,'header_id');
    }

    public function getLineTotalPriceAttribute()
    {
        return (int)$this->quantity * (int)$this->productVariant->price;
    }

}
