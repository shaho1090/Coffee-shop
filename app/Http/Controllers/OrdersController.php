<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function store(Request $request)
    {
        $order = (new Order)->createNew($request->toArray());

        return response([
            'order' => new OrderResource($order->load('productVariant'))
        ]);
    }
}
