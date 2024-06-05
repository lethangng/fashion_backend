<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page, $user_id)
    {
        $products = Favorite::latest()->where('user_id', $user_id)->paginate(4, ['id', 'product_id'], 'page', $page);

        $products = $products->map(function ($product) {
            $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();
            $imageUrl = (new UploadController())->getImage($product->image);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'status' => $product->status,
                'newest' => $product->newest,
                'brand' => $product->brand->name,
                'sell_off' => $productPrice->sell_off,
                'price_off' => $productPrice->price_off,
                'price' => $productPrice->price,
                'image_url' => $imageUrl,
            ];
        });

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $products,
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                Favorite::create($request->all());
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
                $favarite = Favorite::find($request->id);
                $favarite->delete();
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
