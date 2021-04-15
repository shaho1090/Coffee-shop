<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderHeaderCollection;
use App\Http\Resources\OrderHeaderResource;
use App\Models\OrderHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->get();

        return response([
            'orders' => new OrderHeaderCollection($orders)
        ]);
    }

    public function store(Request $request)
    {
        $orderHeader = (new OrderHeader)->createNew()
            ->addLines($request->get('order_data'));

        return response([
            'order' => new OrderHeaderResource($orderHeader->load('lines'))
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(Request $request, OrderHeader $order)
    {
        DB::beginTransaction();

        if (!$order->isInWaitingState()) {
            throw new \Exception('The order can not be changed!');
        }

        $order->updateLines($request->get('order_data'));

        DB::commit();
    }
}
