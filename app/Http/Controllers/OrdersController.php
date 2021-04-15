<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderHeaderCollection;
use App\Http\Resources\OrderHeaderResource;
use App\Models\OrderHeader;
use Illuminate\Http\JsonResponse;
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
            return response()->json([
                'error' => true,
                'message' => 'The order can not be changed!'
            ]);
        }

        $order->updateLines($request->get('order_data'));

        DB::commit();

        $order->refresh();

        return new OrderHeaderResource($order);
    }

    public function destroy($order): JsonResponse
    {
        if (!$order->isInWaitingState()) {
            return response()->json([
                'error' => true,
                'message' => 'The order can not be canceled!'
            ]);
        }

        $order->delete();

        return response()->json([
            'error' => false,
            'message' => 'The order has been canceled!'
        ]);
    }
}
