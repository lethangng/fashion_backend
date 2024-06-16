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
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $is_select = $request->is_select;
        if ($is_select) {
            $deliveryAddress = DeliveryAddress::where('user_id', $user_id)->where('is_select', 1)->select(['id', 'address', 'is_select', 'fullname', 'phone_number'])->first();
        } else {
            $deliveryAddress = DeliveryAddress::where('user_id', $user_id)->latest()->select(['id', 'address', 'is_select', 'fullname', 'phone_number'])->get();
        }

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
            // 'city' => 'required',
            'address' => 'required',
            'fullname' => 'required',
            'phone_number' => 'required',
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
                if ($count == 0) {
                    $request['is_select'] = 1;
                }

                $delivery_address = DeliveryAddress::create($request->all());
                return response()->json([
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => [
                        'msg' => 'Thêm địa chỉ giao hàng thành công'
                    ],
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
        $deliveryAddress = DeliveryAddress::find($id)->only('id', 'address', 'is_select');
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
            // 'city' => 'required',
            'address' => 'required',
            'fullname' => 'required',
            'phone_number' => 'required',
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
                $delivery_address = DeliveryAddress::find($request->id);

                if ($request->is_select == 1) {
                    $delivery_address_list = DeliveryAddress::where('user_id', $delivery_address->user_id)->get();
                    $delivery_address_list->each(function ($delivery_addresss_item) {
                        $delivery_addresss_item->is_select = 0;
                        $delivery_addresss_item->save();
                    });
                }
                // $deliveryAddress->city = $request->city;

                $delivery_address->address = $request->address;
                $delivery_address->fullname = $request->fullname;
                $delivery_address->phone_number = $request->phone_number;
                $delivery_address->is_select = $request->is_select;
                $delivery_address->save();

                return response()->json([
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => [
                        'msg' => 'Cập nhật thành công'
                    ],
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
