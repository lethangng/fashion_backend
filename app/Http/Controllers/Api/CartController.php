<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page;
        $user_id = $request->user_id;
        $limit = $request->limit;

        $carts = Cart::latest()->where('user_id', $user_id)->paginate($limit, ['*'], 'page', $page);

        $carts = $carts->map(function ($cart) {
            $product = Product::find($cart->product_id);
            $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();
            $imageUrl = (new UploadController())->getImage($product->image);
            $extra_product = json_decode($cart->extra_product);

            $size = $extra_product->size;
            $size = Size::find($size)->only('id', 'size');
            $color = $extra_product->color;
            $color = Color::find($color)->only('id', 'name', 'color');

            $sizes = json_decode($product->sizes);
            $sizes = collect($sizes)->map(function ($size) {
                return Size::find($size)->only('id', 'size');
            });

            $colors = json_decode($product->colors);
            $colors = collect($colors)->map(function ($color) {
                return Color::find($color)->only('id', 'name', 'color');
            });

            return [
                'id' => $cart->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'status' => $product->status,
                'brand' => $product->brand->name,
                'sell_off' => $productPrice->sell_off,
                'price_off' => $productPrice->price_off,
                'price' => $productPrice->price,
                'image_url' => $imageUrl,
                'extra_product' => $extra_product,
                'size' => $size,
                'color' => $color,
                'sizes' => $sizes,
                'colors' => $colors,
            ];
        });

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $carts,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'user_id' => 'required',
            'quantity' => 'required',
            'extra_product' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                $extra_product = $request->extra_product;
                $size_id_extra = json_decode($extra_product)->size;
                $color_id_extra = json_decode($extra_product)->color;

                $cart = Cart::where('product_id', $request->product_id)->where('user_id', $request->user_id)->first();

                // return response()->json($extra_product);

                if ($cart) {
                    $size_id = json_decode($cart->extra_product)->size;
                    $color_id = json_decode($cart->extra_product)->color;
                    if ($size_id == $size_id_extra && $color_id == $color_id_extra) {
                        return response()->json([
                            'res' => 'done',
                            'msg' => 'Thành công',
                            'data' => [
                                'msg' => 'Sản phẩm đã tồn tại trong giỏ hàng!',
                            ],
                        ]);
                    } else {
                        Cart::create($request->all());
                        $data = [
                            'res' => 'done',
                            'msg' => 'Thành công',
                            'data' => [
                                'msg' => 'Thêm sản phẩm vào giỏ hàng thành công!'
                            ],
                        ];
                        return response()->json($data, 200);
                    }
                }
                Cart::create($request->all());
                $data = [
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => [
                        'msg' => 'Thêm sản phẩm vào giỏ hàng thành công!'
                    ],
                ];
                return response()->json($data, 200);
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'res' => 'error',
                        'msg' => '',
                        'data' => $e,
                    ]
                );
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
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                $cart = Cart::find($request->id);
                $cart->delete();
                $data = [
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => [],
                ];
                return response()->json($data, 200);
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'res' => 'error',
                        'msg' => '',
                        'data' => $e,
                    ]
                );
            }
        }
    }
}
