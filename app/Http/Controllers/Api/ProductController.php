<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\UploadController;

Product::generateRecommendations('similar_products');

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1)
    {
        $products = Product::latest()->paginate(6, ['id', 'name', 'image', 'brand_id'], 'page', $page);

        $products = $products->map(function ($product) {
            $productPrice = ProductPrice::where('product_id', $product->id)->latest()->first();
            $brandName = Brand::find($product->brand_id)->name;
            $imageUrl = (new UploadController())->getImage($product->image);
            // dd($product);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $brandName,
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
        //
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
