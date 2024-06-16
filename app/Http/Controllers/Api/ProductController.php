<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use App\Models\Evaluate;
use App\Models\Favorite;
use App\Http\Helper\Helper;
use App\Models\OrderProduct;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UploadController;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 6;
        $newest = $request->newest ?? false;
        $sale = $request->sale;
        $user_id = $request->user_id;
        $category_id = $request->category_id;

        // return response()->json($request->all());
        // dd($sale);
        // dd($sale);
        if ($newest) {
            $products = Product::latest()->where('newest', 1)->paginate($limit, ['*'], 'page', $page);
        } else if ($sale != null) {
            $products = Product::latest()->paginate($limit, ['*'], 'page', $page)->filter(function ($product) use ($sale) {
                $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();

                return $productPrice->sell_off != null && $sale != null;
            });
        } else if ($category_id) {
            $products = Product::latest()->where('category_id', $category_id)->paginate($limit, ['*'], 'page', $page);
        } else {
            $products = Product::latest()->paginate($limit, ['*'], 'page', $page);
        }

        $firebaseStorage = new UploadController();
        // $products = new Collection($products);
        $products = $products
            ->map(function ($product) use ($user_id, $firebaseStorage) {
                $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();

                $imageUrl = $firebaseStorage->getImage($product->image);
                if ($user_id) {
                    $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();
                } else {
                    $favorite = false;
                }

                // $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();
                $evaluate = EvaluatesController::countStar($product->id);

                $brand = Brand::find($product->brand_id)->name;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'status' => $product->status,
                    'newest' => $product->newest,
                    // 'brand' => $product->brand->name,
                    'brand' => $brand,
                    'sell_off' => $productPrice->sell_off,
                    'price_off' => $productPrice->price_off,
                    'price' => $productPrice->price,
                    'image_url' => $imageUrl,
                    'favorite' => $favorite,
                    ...$evaluate,
                ];
            })->values();

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
                'size' => $size->size,
            ];
        });

        $colors = json_decode($product->colors);
        $colors = collect($colors)->map(function ($color) {
            $color = Color::find($color);
            $color_value = Helper::convertColor($color->color);

            return [
                'id' => $color->id,
                'color' => $color_value,
                'name' => $color->name,
            ];
        });

        $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();

        $evaluate = EvaluatesController::countStar($product->id);

        if ($user_id) {
            $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();
        } else {
            $favorite = false;
        }

        $sold = OrderProduct::where('product_id', $product->id)->count();

        $brand = Brand::find($product->brand_id)->name;
        $catrgory = Category::find($product->category_id)->name;

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'image_url' => $image_url,
                'list_image_url' => $list_image_url,
                // 'brand' => $product->brand->name,
                'brand' => $brand,
                // 'category' => $product->category->name,
                'category' => $catrgory,
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
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 6;
        $user_id = $request->user_id;
        $product_name = $request->product_name;
        $sort = $request->sort;

        $min_price = $request->min_price;
        $max_price = $request->max_price;

        $colors = $request->colors ? json_decode($request->colors) : [];
        $sizes = $request->sizes ? json_decode($request->sizes) : [];
        $brands = $request->brands ? json_decode($request->brands) : [];
        $categories = $request->categories ? json_decode($request->categories) : [];
        // dd($request->all());

        // Lấy ra các product join vào với product_price với điều kiện product_price.is_select = 1
        $query = Product::join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->where('product_prices.is_select', 1)
            ->where('name', 'like', '%' . $product_name . '%');


        if ($min_price && $max_price) {
            $query->whereBetween('price', [$min_price, $max_price]);
        }

        if (count($categories) > 0) {
            $query->whereIn('category_id', $categories);
        }

        if (count($brands) > 0) {
            $query->whereIn('brand_id', $brands);
        }

        if (count($colors) > 0) {
            $query->orWhereJsonContains('colors', $colors);
        }

        if (count($sizes) > 0) {
            $query->orWhereJsonContains('sizes', $sizes);
        }

        switch ($sort) {
            case 0:
                $query->orderBy('products.created_at', 'desc');
                break;
            case 1:
                $query->orderBy('products.created_at', 'asc');
                break;
            case 2:
                $query->orderBy('price', 'desc');
                break;
            case 3:
                $query->orderBy('price', 'asc');
                break;
            default:
                $query->orderBy('products.created_at', 'desc');
                break;
        }

        // if ($sort == 0) {
        //     $query->orderBy('products.created_at', 'desc');
        // } else if ($sort == 1) {
        //     $query->orderBy('price', 'asc');
        // } else if ($sort == 2) {
        //     $query->orderBy('price', 'desc');
        // }

        $filter_products = $query->paginate($limit, ['*'], 'page', $page);

        $firebaseStorage = new UploadController();
        $products = $filter_products
            ->map(function ($product) use ($user_id, $firebaseStorage) {
                $imageUrl = $firebaseStorage->getImage($product->image);

                if ($user_id) {
                    $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();
                } else {
                    $favorite = false;
                }

                // $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();
                $evaluate = EvaluatesController::countStar($product->id);

                $brand = Brand::find($product->brand_id)->name;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'status' => $product->status,
                    'newest' => $product->newest,
                    // 'brand' => $product->brand->name,
                    'brand' => $brand,
                    'sell_off' => $product->sell_off,
                    'price_off' => $product->price_off,
                    'price' => $product->price,
                    'image_url' => $imageUrl,
                    'favorite' => $favorite,
                    ...$evaluate,
                ];
            })->values();

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $products,
        ];
        return response()->json($data, 200);
    }

    public function getRecommendations(Request $request)
    {
        $product_id = $request->product_id;
        $user_id = $request->user_id;
        $product = Product::find($product_id);
        $recommendations = $product->getRecommendations('similar_products');

        $firebaseStorage = new UploadController();

        $recommendations = $recommendations
            ->map(function ($product) use ($user_id, $firebaseStorage) {
                $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();

                $imageUrl = $firebaseStorage->getImage($product->image);

                if ($user_id) {
                    $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();
                } else {
                    $favorite = false;
                }

                // $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product->id)->exists();
                $evaluate = EvaluatesController::countStar($product->id);

                $brand = Brand::find($product->brand_id)->name;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'status' => $product->status,
                    'newest' => $product->newest,
                    // 'brand' => $product->brand->name,
                    'brand' => $brand,
                    'sell_off' => $productPrice->sell_off,
                    'price_off' => $productPrice->price_off,
                    'price' => $productPrice->price,
                    'image_url' => $imageUrl,
                    'favorite' => $favorite,
                    ...$evaluate,
                ];
            })->values();

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $recommendations,
        ];

        return response()->json($data, 200);
    }
}
