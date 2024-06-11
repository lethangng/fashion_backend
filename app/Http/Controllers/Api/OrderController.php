<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page, $user_id, $status)
    {
        $orders = Order::where('user_id', $user_id)->where('status', $status)->latest()->paginate(6, ['*'], 'page', $page);

        $orders = $orders->map(function ($order) {
            $order_products = OrderProduct::where('order_id', $order->id);
            $count = $order_products->count();

            $order_product = $order_products->first();
            $product = Product::find($order_product->product_id);
            $extra_product = json_decode($order_product->extra_product);

            $size = $extra_product->size;
            $size = Size::find($size)->only('id', 'size');
            $color = $extra_product->color;
            $color = Color::find($color)->only('id', 'name', 'color');

            $imageUrl = (new UploadController())->getImage($product->image);
            return [
                'id' => $order->id,
                'product_name' => $product->name,
                'quantity' => $order_product->quantity,
                'price' => $order_product->price,
                'image_url' => $imageUrl,
                'color' => $color,
                'size' => $size,
                'count' => $count,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'price_off' => $order->price_off,
                'created_at' => date('d/m/Y H:i', strtotime($order->created_at)),
            ];
        });

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $orders,
        ];

        return response()->json($data, 200);
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
            'price_off' => 'required',
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
                    $order_product_val->extra_product = json_encode($order_product['extra_product']);
                    $order_product_val->save();

                    $total_price += $order_product['price'] * $order_product['quantity'];
                }

                $order->total_price = $total_price;
                $order->save();

                return response()->json([
                    'res' => 'done',
                    'msg' => 'Thành công',
                    // 'data' => $order,
                    'data' => [
                        'msg' => 'Đặt hàng thành công',
                    ]
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
        $order = Order::find($id);
        $order_products = OrderProduct::where('order_id', $order->id);
        $count = $order_products->count();

        $order_products = $order_products->get()->map(function ($order_product) {
            $product = Product::find($order_product->product_id);
            $extra_product = json_decode($order_product->extra_product);

            $size = $extra_product->size;
            $size = Size::find($size)->only('id', 'size');
            $color = $extra_product->color;
            $color = Color::find($color)->only('id', 'name', 'color');

            $imageUrl = (new UploadController())->getImage($product->image);
            return [
                'id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $order_product->quantity,
                'price' => $order_product->price,
                'image_url' => $imageUrl,
                'color' => $color,
                'size' => $size
            ];
        });

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => [
                'order_info' => [
                    'id' => $order->id,
                    'count' => $count,
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'price_off' => $order->price_off,
                    'created_at' => date('d/m/Y H:i', strtotime($order->created_at)),
                ],
                'order_list' => $order_products,
            ],
        ];

        return response()->json($data, 200);
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
