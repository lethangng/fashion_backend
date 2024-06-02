<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Models\Brand;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    public function index($page = 1): View
    {
        $brands = Brand::latest()->paginate(20, ['*'], 'page', $page);
        $total_pages = $brands->lastPage();
        $current_page = $brands->currentPage();

        return view('admin.brand.index', compact('brands', 'total_pages', 'current_page'));
    }

    public function create(): View
    {
        return view('admin.brand.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'description' => 'required'
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            // 'description.required' => 'Vui lòng nhập mô tả.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            // $brand = new Brand($request->all());
            // $brand->save();
            $brand = Brand::create($request->all());

            if ($brand instanceof Brand) {
                toastr()->success('Thêm thương hiệu mới thành công!');

                return redirect()->route('brand.index');
            }

            toastr()->error('Thêm thương hiệu mới thất bại.');

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        // dd($id);
        if (!$id) {
            toastr()->error('Lấy thông tin thương hiệu thất bại.');
            return back();
        } else {
            $brand = Brand::find($id);
            return view('admin.brand.edit', compact('brand'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
        ]);

        $brand = Brand::find($request->id);
        $brand->update($request->all());

        toastr()->success('Sửa thành công!');
        return redirect()->route('brand.index');
    }

    /** 
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (request()->ajax()) {
            $ids = $request->id;
            Brand::whereIn('id', $ids)->delete();
            toastr()->success('Xóa thành công!');
            return response()->json($request->id);
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            toastr()->error('Thêm thương hiệu mới thất bại.');
            throw new ValidationException($validator);
        } else {
            $brand = Brand::find($request->id);

            $result = $brand->delete();
            if (is_bool($result)) {
                toastr()->success('Xóa thành công!');
            } else {
                toastr()->error('Xóa thất bại.');
            }
            return redirect()->route('brand.index');
        }
    }

    public function search(Request $request)
    {

        $search = trim($request->search);
        $search = preg_replace('/\s+/', ' ', $search);
        $brands = Brand::where('name', 'like', '%' . $search . '%')->paginate(10);

        return view('admin.brand.index', compact('brands', 'search'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public static function getBrandById($id)
    {
        return Brand::findOrFail($id);
    }
}
