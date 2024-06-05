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
    public function index($page)
    {
        $coupons = Coupon::latest()->simplePaginate(4, ['*'], 'page', $page);

        $coupons = $coupons->map(function ($coupon) {
            return [
                'id' => $coupon->id,
                'name' => $coupon->name,
                'code' => $coupon->code,
                'price' => $coupon->price,
                'for_sum' => $coupon->for_sum,
                'coupon_type' => $coupon->coupon_type,
                'expired' => $coupon->expired,
                'description' => $coupon->description,
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
