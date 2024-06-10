<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 4;
        $coupons = Coupon::latest()->simplePaginate($limit, ['*'], 'page', $page);

        $coupons = $coupons->map(function ($coupon) {
            return [
                'id' => $coupon->id,
                'name' => $coupon->name,
                'code' => $coupon->code,
                'price' => $coupon->price,
                'for_sum' => $coupon->for_sum,
                'coupon_type' => $coupon->coupon_type,
                'expired' => date('d/m/Y', strtotime($coupon->expired)),
                'desc' => $coupon->description,
            ];
        });

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $coupons,
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
