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
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Admin\Category\CategoryController;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1)
    {
        $products = Product::latest()->paginate(20, ['id', 'name', 'category_id', 'image', 'import_price'], 'page', $page);

        $total_pages = $products->lastPage();
        $current_page = $products->currentPage();

        // dd($current_page);

        $products = $products->map(function ($product) {
            $categoryName = CategoryController::getCategoryById($product->category_id)->name;
            $imageUrl = (new UploadController())->getImage($product->image);
            $productPrice = ProductPrice::where('product_id', $product->id)->where('is_select', 1)->first()->price;
            // dd($product);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'import_price' => $product->import_price,
                'category_name' => $categoryName,
                'image_url' => $imageUrl,
                'product_price' => $productPrice,
            ];
        });

        return view('admin.products.index', compact('products', 'total_pages', 'current_page'));
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category_id' => 'required',
                'brand_id' => 'required',
                'status' => 'required',
                'newest' => 'required',
                'price' => 'required',
                'import_price' => 'required',
                'image_product' => 'required',
            ]
            // , [
            //     'name.required' => 'Vui lòng nhập tên sản phẩm.',
            // ]
        );

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
                $request['list_images'] = json_encode($list_images);

                // return response()->json($request->all());
                $product = Product::create($request->all());

                ProductPrice::create([
                    'product_id' => $product->id,
                    'price' => $price,
                    'price_off' => $price_off ?? 0,
                    'sell_off' => $sell_off,
                ]);

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
        $product = Product::find($id);
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();

        $firebaseStorage = new UploadController();

        $image_url = json_encode([
            'image_name' => $product->image,
            'image_url' => $firebaseStorage->getImage($product->image),
        ]);

        $list_image_url = json_decode($product->list_images);
        $list_image_url = collect($list_image_url)->map(function ($item) {
            return [
                'image_name' => $item,
                'image_url' => (new UploadController())->getImage($item),
            ];
        });

        // dd($image_url);
        return view('admin.products.edit', compact('categories', 'brands', 'colors', 'sizes', 'product', 'image_url', 'list_image_url'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return response()->json($$request->only('list_images_product_url'));
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'status' => 'required',
            'newest' => 'required',
            'import_price' => 'required',
            'image_product' => 'required',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'mes' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                $product = Product::find($request->id);
                // return response()->json($product);

                $firebaseStorage = new UploadController();
                $firebase_storage_path = 'images/';

                if (!$request->hasFile('image_product')) {
                    if ($request->image_product != $product->image) {
                        return response()->json([
                            'res' => 'error',
                            'mes' => 'Vui lòng thêm hình ảnh sản phẩm',
                            'data' => [],
                        ]);
                    } else {
                        $request['image'] = $request->image_product;
                    }
                } else {
                    // return response()->json('ok');
                    // $firebaseStorage->destroy($product->image);
                    $image = $request->file('image_product');

                    $downloadUrl = $firebaseStorage->upload($image, $firebase_storage_path);
                    $request['image'] = $downloadUrl;
                }


                $list_images = [];

                $arrayListImage = json_decode($product->list_images);
                $listImagesProductUrl = $request->list_images_product_url;
                // foreach ($request->list_images_product as $image) {
                //     if (is_file($image)) {
                //         $downloadUrl = $firebaseStorage->upload($image, $firebase_storage_path);
                //         $list_images[] = $downloadUrl;
                //     }
                // }

                foreach ($request->only('list_images_product') as $images) {
                    foreach ($images as $image) {
                        if (is_file($image)) {
                            $downloadUrl = $firebaseStorage->upload($image, $firebase_storage_path);
                            $list_images[] = $downloadUrl;
                        }
                    }
                }

                foreach ($arrayListImage as $image) {
                    if (in_array($image, $listImagesProductUrl)) {
                        $list_images[] = $image;
                    } else {
                        // $firebaseStorage->destroy($image);
                    }
                }

                $request['list_images'] = json_encode($list_images);

                // return response()->json($request->all());
                $product->update($request->all());

                toastr()->success('Sửa thành công!');
            } catch (\Exception $e) {
                toastr()->error('Sửa thất bại.');
                return response()->json([
                    'res' => 'error',
                    'mes' => '',
                    'data' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return response()->json($request->all());

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        $firebaseStorage = new UploadController();
        if (request()->ajax()) {
            try {
                $ids = $request->id;
                foreach ($ids as $id) {
                    $product = Product::find($id);
                    // $firebaseStorage->destroy($product->image);

                    $list_images = json_decode($product->list_images);

                    foreach ($list_images as $image) {
                        // $firebaseStorage->destroy($image);
                    }

                    $product->delete();
                }
                toastr()->success('Xóa thành công!');
                return response()->json($request->id);
            } catch (\Exception $e) {
                return response()->json($e);
            }
        }

        if ($validator->fails()) {
            toastr()->error('Không tìm thấy.');
            throw new ValidationException($validator);
        } else {
            try {
                $product = Product::find($request->id);

                $firebaseStorage->destroy($product->image);

                $list_images = json_decode($product->list_images);

                foreach ($list_images as $image) {
                    $firebaseStorage->destroy($image);
                }

                $product->delete();
                toastr()->success('Xóa thành công!');
                return redirect()->route('product.index');
            } catch (\Exception $e) {
                dd($e);
                toastr()->error('Xóa thất bại.');
            }
        }
    }

    public function search(Request $request, $page = 1)
    {
        $search = trim($request->search);
        $search = preg_replace('/\s+/', ' ', $search);
        $products = Product::where('name', 'like', '%' . $search . '%')->latest()->paginate(20, ['id', 'name', 'category_id', 'image', 'import_price'], 'page', $page);

        $total_pages = $products->lastPage();
        $current_page = $products->currentPage();

        // dd($current_page);

        $products = $products->map(function ($product) {
            $categoryName = CategoryController::getCategoryById($product->category_id)->name;
            $imageUrl = (new UploadController())->getImage($product->image);
            // dd($product);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'import_price' => $product->import_price,
                'category_name' => $categoryName,
                'import_price' => $product->import_price,
                'image_url' => $imageUrl,
            ];
        });

        return view('admin.products.index', compact('products', 'total_pages', 'current_page', 'search'));
    }

    public function statistical(int $year)
    {
        $thong_ke = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = Product::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
            $thong_ke[] = $count;
        }

        return $thong_ke;
    }
}
