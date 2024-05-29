<?php

namespace App\Http\Controllers\Admin\Coupon;

use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1)
    {
        $coupons = Coupon::latest()->paginate(20, ['*'], 'page', $page);
        $total_pages = $coupons->lastPage();
        $current_page = $coupons->currentPage();

        return view('admin.coupon.index', compact('coupons', 'total_pages', 'current_page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupon.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'price' => 'required',
            'for_sum' => 'required',
            'coupon_type' => 'required',
            'expired' => 'required',
        ], [
            'code.required' => 'Vui lòng nhập code.',
            'price.required' => 'Vui lòng nhập giá trị giảm.',
            'for_sum.required' => 'Vui lòng nhập giảm giá theo giá trị.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            try {
                $date = Carbon::createFromFormat('d-m-Y', $request->expired)->format('Y-m-d');
                $request['expired'] = $date;

                Coupon::create($request->all());
                toastr()->success('Thêm thành công!');
                return redirect()->route('coupon.index');
            } catch (\Exception $e) {
                dd($e);
                toastr()->error('Thêm thất bại.');
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
            toastr()->error('Lấy thông tin màu sắc thất bại.');
            return back();
        } else {
            $coupon = Coupon::find($id);
            return view('admin.coupon.edit', compact('coupon'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'percent' => 'required',
            'for_sum' => 'required',
        ], [
            'code.required' => 'Vui lòng nhập code.',
            'percent.required' => 'Vui lòng nhập giá trị giảm.',
            'for_sum.required' => 'Vui lòng nhập giảm giá theo giá trị.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            try {
                $coupon = Coupon::find($request->id);
                $coupon->update($request->all());

                toastr()->success('Sửa thành công!');
                return redirect()->route('coupon.index');
            } catch (\Exception $e) {
                // dd($e);
                toastr()->error('Sửa thất bại.');
                return back();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (request()->ajax()) {
            $ids = $request->id;
            Coupon::whereIn('id', $ids)->delete();
            toastr()->success('Xóa thành công!');
            return response()->json($request->id);
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            toastr()->error('Xóa thất bại.');
            throw new ValidationException($validator);
        } else {
            try {
                $coupon = Coupon::find($request->id);
                $coupon->delete();
                toastr()->success('Xóa thành công!');
                return redirect()->route('coupon.index');
            } catch (\Exception $e) {
                toastr()->error('Xóa thất bại.');
            }
        }
    }
}
