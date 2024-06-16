<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Product;
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
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $user_id = $request->user_id ?? 1;
        $limit = $request->limit ?? 4;

        $favorites = Favorite::latest()->where('user_id', $user_id)->paginate($limit, ['id', 'product_id'], 'page', $page);

        $firebaseStorage = new UploadController();

        // dd($favorites);

        $favorites = $favorites->map(function ($favorite) use ($firebaseStorage) {
            $product = Product::find($favorite->product_id);
            $productPrice = ProductPrice::where('product_id', $favorite->product_id)->latest()->first();
            // dd($product);
            $imageUrl = $firebaseStorage->getImage($product->image);

            $evaluate = EvaluatesController::countStar($product->id);
            $brand = Brand::find($product->brand_id)->name;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'status' => $product->status,
                'newest' => $product->newest,
                'brand' => $brand,
                'sell_off' => $productPrice->sell_off,
                'price_off' => $productPrice->price_off,
                'price' => $productPrice->price,
                'image_url' => $imageUrl,
                'favorite' => true,
                ...$evaluate,
            ];
        });

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $favorites,
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
                $favorite = Favorite::where('product_id', $request->product_id)->where('user_id', $request->user_id)->first();
                if ($favorite) {
                    $favorite->delete();
                    return response()->json([
                        'res' => 'done',
                        'msg' => 'Xóa khỏi yêu thích thành công',
                        'data' => [
                            'msg' => 'Xóa khỏi yêu thích thành công',
                        ],
                    ]);
                }
                Favorite::create($request->all());
                $data = [
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => [
                        'msg' => 'Thêm vào yêu thích thành công.'
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
                $favorite = Favorite::find($request->id);
                $favorite->delete();
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
