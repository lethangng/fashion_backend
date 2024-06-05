<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DeliveryAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user_id)
    {
        $deliveryAddress = DeliveryAddress::where('user_id', $user_id)->latest()->select(['id', 'city', 'address', 'is_select'])->get();

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $deliveryAddress,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'city' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                $count = DeliveryAddress::where('user_id', $request->user_id)->count();
                if ($count == 5) {
                    return response()->json([
                        'res' => 'error',
                        'msg' => 'Không thể tạo quá 5 địa chỉ giao hàng.',
                        'data' => [],
                    ]);
                }

                $delivery_address = DeliveryAddress::create($request->all());
                return response()->json([
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => $delivery_address,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'res' => 'error',
                    'msg' => '',
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
        $deliveryAddress = DeliveryAddress::find($id)->only('id', 'city', 'address', 'is_select');
        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $deliveryAddress,
        ];

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'city' => 'required',
            'address' => 'required',
            'is_select' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                $deliveryAddress = DeliveryAddress::find($request->id);
                $deliveryAddress->city = $request->city;
                $deliveryAddress->address = $request->address;
                $deliveryAddress->is_select = $request->is_select;
                $deliveryAddress->save();

                return response()->json([
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => $deliveryAddress,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'res' => 'error',
                    'msg' => '',
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
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                $cart = DeliveryAddress::find($request->id);
                $cart->delete();
                $data = [
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => [],
                ];
                return response()->json($data, 200);
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'res' => 'error',
                        'msg' => '',
                        'data' => $e,
                    ]
                );
            }
        }
    }
}
