<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderHeaderCollection;
use App\Http\Resources\OrderHeaderResource;
use App\Models\OrderHeader;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->get();

        return new OrderHeaderCollection($orders);
    }

    public function store(Request $request)
    {
        $orderHeader = (new OrderHeader)->createNew()
            ->addLines($request->get('order_data'));

        return response([
            'order' => new OrderHeaderResource($orderHeader->load('lines'))
        ]);
    }
}
