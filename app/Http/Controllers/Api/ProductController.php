<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\Evaluate;
use App\Models\Favorite;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UploadController;
use App\Models\OrderProduct;

class ProductController extends Controller
{
    public function genRecomment()
    {
        Product::generateRecommendations('similar_products');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 6;
        $newest = $request->newest ?? false;
        $sale = $request->sale ?? false;
        $user_id = $request->user_id ?? 1;
        $category_id = $request->category_id;

        // return response()->json($request->all());
        if ($newest) {
            $products = Product::latest()->where('newest', 1)->paginate($limit, ['id', 'name', 'image', 'brand_id', 'status', 'newest'], 'page', $page);
        } else if ($sale) {
            $products = Product::latest()->whereNotNull('sell_off')->paginate($limit, ['id', 'name', 'image', 'brand_id', 'status', 'newest'], 'page', $page);
        } else if ($category_id) {
            $products = Product::latest()->where('category_id', $category_id)->paginate($limit, ['id', 'name', 'image', 'brand_id', 'status', 'newest'], 'page', $page);
        } else {
            $products = Product::latest()->paginate($limit, ['id', 'name', 'image', 'brand_id', 'status', 'newest'], 'page', $page);
        }

        $firebaseStorage = new UploadController();

        $products = $products->map(function ($product) use ($user_id, $firebaseStorage) {
            $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();
            $imageUrl = $firebaseStorage->getImage($product->image);

            $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();
            $evaluate = EvaluatesController::countStar($product->id);

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
                'favorite' => $favorite,
                ...$evaluate,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->id;
        $user_id = $request->user_id;
        $product = Product::find($id);

        $firebaseStorage = new UploadController();

        $image_url = $firebaseStorage->getImage($product->image);

        $list_image_url = json_decode($product->list_images);
        $list_image_url = collect($list_image_url)->map(function ($item) use ($firebaseStorage) {
            return
                // 'image_name' => $item,
                // 'image_url' => $firebaseStorage->getImage($item),
                $firebaseStorage->getImage($item);
        });

        $sizes = json_decode($product->sizes);
        $sizes = collect($sizes)->map(function ($size) {
            $size = Size::find($size);
            return [
                'id' => $size->id,
                'name' => $size->size,
            ];
        });

        $colors = json_decode($product->colors);
        $colors = collect($colors)->map(function ($color) {
            $color = Color::find($color);
            return [
                'id' => $color->id,
                'color' => $color->color,
                'name' => $color->name,
            ];
        });

        $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();

        $evaluate = EvaluatesController::countStar($product->id);

        $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();

        $sold = OrderProduct::where('product_id', $product->id)->count();

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'image_url' => $image_url,
                'list_image_url' => $list_image_url,
                'brand' => $product->brand->name,
                'category' => $product->category->name,
                'sizes' => $sizes,
                'colors' => $colors,
                'price' => $productPrice->price,
                'sell_off' => $productPrice->sell_off,
                'price_off' => $productPrice->price_off,
                'desc' => $product->description,
                'status' => $product->status,
                'newest' => $product->newest,
                'favorite' => $favorite,
                'sold' => $sold,
                ...$evaluate,
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

    public function filter(Request $request)
    {
        $minPrice = $request->minPrice;
        $maxPrice = $request->maxPrice;

        // $products = Product::whereBetween('price', [$minPrice, $maxPrice])
        //     ->get();
    }

    public function getRecommendations(string $id)
    {
        $product        = Product::find($id);
        $recommendations = $product->getRecommendations('similar_products');

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $recommendations,
        ];

        return response()->json($data, 200);
    }
}
