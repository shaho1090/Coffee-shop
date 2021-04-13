<?php

namespace App\Http\Resources;

use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHeaderResource extends JsonResource
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
            'date' => Carbon::parse($this->date)->toDateTimeString(),
            'customer' => new UserResource($this->customer),
            'status' => $this->status,
            'total_price' => $this->total_price,
            'lines' => new OrderLineCollection($this->lines),
        ];
    }
}
