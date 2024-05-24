<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Admin\Brand\BrandController;
use App\Http\Controllers\Admin\Category\CategoryController;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1)
    {
        $products = Product::latest()->paginate(10, ['id', 'name', 'category_id', 'brand_id', 'image'], 'page', $page);
        $products = $products->map(function ($product) {
            // $category = (new CategoryController())->getCategoryById($product->category_id);
            $categoryName = CategoryController::getCategoryById($product->category_id)->name;
            $brandName = BrandController::getBrandById($product->category_id)->name;
            $imageUrl = (new UploadController())->getImage($product->image);
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'category_name' => $categoryName,
                'brand_name' => $brandName,
                'image_url' => $imageUrl,
            ];
        });
        // dd($products);
        return view('admin.products.index', compact('products'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('admin.products.add', compact('categories', 'brands', 'colors', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'status' => 'required',
            'newest' => 'required',
            'price' => 'required',
            'image_product' => 'required',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
        ]);

        if (!$request->hasFile('image_product')) {
            return response()->json([
                'res' => 'error',
                'mes' => 'Vui lòng thêm hình ảnh sản phẩm',
                'data' => [],
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'mes' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                $price = $request->price;
                $price_off = $request->price_off;
                $sell_off = $request->sell_off;
                unset($request['price']);
                unset($request['price_off']);
                unset($request['sell_off']);

                $image = $request->file('image_product');
                $firebaseStorage = new UploadController();
                $firebase_storage_path = 'images/';

                $downloadUrl = $firebaseStorage->upload($image, $firebase_storage_path);
                $request['image'] = $downloadUrl;

                $list_images = [];
                foreach ($request->only('list_images_product') as $images) {
                    foreach ($images as $image) {
                        if (is_file($image)) {
                            $downloadUrl = $firebaseStorage->upload($image, $firebase_storage_path);
                            $list_images[] = $downloadUrl;
                        }
                    }
                }
                $request['list_image'] = json_encode($list_images);

                // return response()->json($request->all());
                $product = Product::create($request->all());

                $product_price = new ProductPrice();
                $product_price->product_id = $product->id;
                $product_price->price = $price;
                $product_price->price_off = $price_off;
                $product_price->sell_off = $sell_off;
                $product_price->save();

                toastr()->success('Thêm sản phẩm thành công!');
            } catch (\Exception $e) {
                toastr()->error('Thêm sản phẩm thất bại.');
                return response()->json([
                    'res' => 'error',
                    'mes' => '',
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    public function search(Request $request)
    {

        $search = trim($request->search);
        $search = preg_replace('/\s+/', ' ', $search);
        $categories = Product::where('name', 'like', '%' . $search . '%')->paginate(10);

        return view('admin.products.index', compact('products', 'search'))->with('i', (request()->input('page', 1) - 1) * 10);
    }
}
