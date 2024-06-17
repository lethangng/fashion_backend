<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Size;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Http\Helper\Helper;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\NotificationController;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $user_id = $request->user_id;
        $status = $request->status ?? 0;
        $limit = $request->limit ?? 3;

        // $orders = Order::where('user_id', $user_id)->where('status', $status)->latest()->paginate($limit, ['*'], 'page', $page);
        $query = Order::where('user_id', $user_id);

        // 0: Hoàn tất, 1: các trạng thái khác, còn lại là Đã hủy
        if ($status == 0) {
            $query->whereIn('status', [4]);
        } else if ($status == 1) {
            $query->whereIn('status', [0, 1, 2, 3]);
        } else {
            $query->whereIn('status', [5]);
        }

        $orders = $query->latest()->paginate($limit, ['*'], 'page', $page);

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
            $brand = Brand::find($product->brand_id)->name;

            $imageUrl = (new UploadController())->getImage($product->image);
            return [
                'id' => $order->id,
                'order_info' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'brand' => $brand,
                    'quantity' => $order_product->quantity,
                    'price' => $order_product->price,
                    'image_url' => $imageUrl,
                    'color' => $color,
                    'size' => $size,
                ],
                'count' => $count,
                'status' => $order->status,
                'status_title' => Helper::statusTitle($order->status),
                'total_price' => $order->total_price,
                'price_off' => $order->price_off,
                'created_at' => date('d-m-Y', strtotime($order->created_at)),
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

                Cart::where('user_id', $request->user_id)->delete();

                $user_device_token = User::find($request->user_id)->device_token;
                // dd($user_device_token);
                $firebaseStorage = new UploadController();

                if ($user_device_token) {
                    $order_product = OrderProduct::where('order_id', $order->id)->first();
                    $product = Product::find($order_product->product_id);
                    $imageUrl = $firebaseStorage->getImage($product->image);

                    $notify = new NotificationController();
                    $notify->sendMessage($user_device_token, 'Thông báo', 'Đặt hàng thành công', $imageUrl);
                }

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
    public function show(Request $request)
    {
        $id = $request->id;
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
            $brand = Brand::find($product->brand_id)->name;

            $imageUrl = (new UploadController())->getImage($product->image);
            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'brand' => $brand,
                'quantity' => $order_product->quantity,
                'price' => $order_product->price,
                'image_url' => $imageUrl,
                'color' => $color,
                'size' => $size,
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
                    'status_title' => Helper::statusTitle($order->status),
                    'total_price' => $order->total_price,
                    'price_off' => $order->price_off,
                    'delivery_address' => $order->delivery_address,
                    'is_evaluate' => $order->is_evaluate,
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
    public function update(Request $request)
    {
        $id = $request->id;
        try {
            $order = Order::find($id);
            $order->status = 4;
            $order->save();

            return response()->json([
                'res' => 'done',
                'msg' => 'Thành công',
                'data' => [
                    'msg' => 'Cập nhật thành công',
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cancel(Request $request)
    {
        $id = $request->id;
        try {
            $order = Order::find($id);
            if ($order->status == 0) {
                $order->status = 5;
                $order->save();
            }

            return response()->json([
                'res' => 'done',
                'msg' => 'Thành công',
                'data' => [
                    'msg' => 'Hủy đơn hàng thành công',
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
