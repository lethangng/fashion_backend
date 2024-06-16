<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $product_id, string $page)
    {
        $product_name = Product::find($product_id)->name;
        $product_prices = ProductPrice::where('product_id', $product_id)->latest()->paginate(10, ['*'], 'page', $page);
        $total_pages = $product_prices->lastPage();
        $current_page = $product_prices->currentPage();

        return view('admin.product_prices.index', compact('product_prices', 'total_pages', 'current_page', 'product_id', 'product_name'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $product_id)
    {
        return view('admin.product_prices.add', compact('product_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request['price_off'] = $request->price_off ?? 0;
        $validator = Validator::make($request->all(), [
            'price' => 'required',
            'product_id' => 'required',
        ], [
            'price.required' => 'Vui lòng nhập giá bán.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            try {
                ProductPrice::create($request->all());
                toastr()->success('Thêm giá mới thành công!');

                return redirect()->route('product_price.index', ['product_id' => $request->product_id, 'page' => 1]);
            } catch (\Exception $e) {
                toastr()->error('Thêm giá mới thất bại.');
                return back();
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
        if (!$id) {
            toastr()->error('Lấy thông tin thất bại.');
            return back();
        } else {
            $product_price = ProductPrice::find($id);
            return view('admin.product_prices.edit', compact('product_price'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id' => 'required',
            'price' => 'required',
        ]);

        $product_price = ProductPrice::find($request->id);
        $product_price->update($request->all());

        toastr()->success('Sửa thành công!');
        return redirect()->route('product_price.index', ['product_id' => $product_price->product_id, 'page' => 1]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (request()->ajax()) {
            $ids = $request->id;
            ProductPrice::whereIn('id', $ids)->delete();
            toastr()->success('Xóa thành công!');
            return response()->json($request->id);
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            toastr()->error('Không tìm thấy thông tin.');
            throw new ValidationException($validator);
        } else {
            $product_price = ProductPrice::find($request->id);

            $result = $product_price->delete();
            if (is_bool($result)) {
                toastr()->success('Xóa thành công!');
            } else {
                toastr()->error('Xóa thất bại.');
            }
            return redirect()->route('product_price.index', ['product_id' => $request->product_id, 'page' => 1]);
        }
    }
}
