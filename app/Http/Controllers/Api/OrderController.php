<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_address' => 'required',
            'user_id' => 'required',
            'order_products' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                // return response()->json($request->all());

                $order = Order::create($request->all());
                $order_products = $request->order_products;
                $order_products = json_decode($order_products, true);

                $total_price = 0;

                foreach ($order_products as $order_product) {
                    $order_product_val = new OrderProduct();
                    $order_product_val->order_id = $order->id;
                    $order_product_val->product_id = $order_product['product_id'];
                    $order_product_val->price = $order_product['price'];
                    $order_product_val->quantity = $order_product['quantity'];
                    $order_product_val->save();

                    $total_price += $order_product['price'] * $order_product['quantity'];
                }

                $order->total_price = $total_price;
                $order->save();

                return response()->json([
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => $order,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'res' => 'error',
                    'msg' => '',
                    'data' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
