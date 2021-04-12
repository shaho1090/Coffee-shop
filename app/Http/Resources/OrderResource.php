<?php

namespace App\Http\Resources;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'product_variant' => new ProductVariantResource($this->whenLoaded('productVariant')),
            'total_price' => $this->total_price
        ];
    }
}
