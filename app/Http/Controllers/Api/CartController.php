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
    public function index($page, $user_id)
    {
        $carts = Cart::latest()->where('user_id', $user_id)->paginate(4, ['id', 'product_id', 'extra_product', 'quantity'], 'page', $page);

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
                Cart::create($request->all());
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
