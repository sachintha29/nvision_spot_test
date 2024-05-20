<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendOrderToApi;


class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $processId = rand(1, 10);

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'order_value' => $request->order_value,
            'process_id' => $processId,
        ]);

        $orderdetails = Order::find($order->id);

        $orderData = [
            'Order_ID' => $orderdetails->id,
            'Customer_Name' => $orderdetails->customer_name,
            'Order_Value' => $orderdetails->order_value,
            'Order_Date' => $orderdetails->created_at->toDateTimeString(),
            'Order_Status' => $orderdetails->order_status,
            'Process_ID' => $orderdetails->process_id,
        ];

        // Dispatch the job to the queue
        SendOrderToApi::dispatch($order);

        return response()->json([
            'Order_ID' => $order->id,
            'Process_ID' => $processId,
            'Status' => 'Success',
            'SendData'=> $orderData,
        ], 201);
    }
}
