<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UserController extends Controller
{
    private $auth;

    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    // public function appData(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'u_id' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'res' => 'error',
    //             'msg' => 'Thất bại',
    //             'data' => [],
    //         ], 200);
    //     }

    //     try {
    //         $user = User::where('u_id', $request->u_id)->first();

    //         $order = Order::where('user_id', $user->id)->get();
    //         $total_order = $order->count();

    //         $delivery_address = DeliveryAddress::where('user_id', $user->id)->get();
    //         $total_delevery_address = $delivery_address->count();

    //         $data = [
    //             'res' => 'done',
    //             'msg' => 'Thành công',
    //             'data' => [
    //                 'total_order' => $total_order,
    //                 'total_delevery_address' => $total_delevery_address,
    //             ],
    //         ];

    //         return response()->json($data);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'res' => 'error',
    //             'msg' => '',
    //             'data' => $e->getMessage(),
    //         ]);
    //     }
    // }

    public function userInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_id' => 'required',
            'device_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Thất bại',
                'data' => [],
            ], 200);
        }

        try {
            $user = User::where('u_id', $request->u_id)->first();

            // Lấy device_token của máy gửi lên để update vào csdl
            if ($request->device_token != $user->device_token) {
                $user->device_token = $request->device_token;
                $user->save();
            }

            $validator = Validator::make(['image' => $user->image], [
                'image' => 'url',
            ]);

            // Kiểm tra xem image lưu trong CSDL có phải là 1 URL không
            if ($validator->fails() && $user->image) {
                $firebaseStorage = new UploadController();
                $imageUrl = $firebaseStorage->getImage($user->image);
                $user['image'] = $imageUrl;
            }

            $data = [
                'res' => 'done',
                'msg' => 'Thành công',
                'data' => $user,
            ];


            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $e->getMessage(),
            ]);
        }
    }

    public function updateInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            // 'u_id' => 'required',
            'fullname' => 'required|min:10|max:30',
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Cập nhập thất bại',
                'data' => [],
            ], 200);
        }

        try {
            // $userProperties = [
            //     'phoneNumber' => '+84' . substr($request->phone_number, 1),
            //     'displayName' => $request->fullname,
            // ];

            // $this->auth->updateUser($request->u_id, $userProperties);

            // $user = User::where('u_id', $request->u_id)->first();
            $data = [
                'phone_number' => $request->phone_number,
                'fullname' => $request->fullname,
            ];

            if ($request->image) {
                $data['image'] = $request->image;
            }

            $user = User::find($request->user_id);
            $user->update($data);

            $firebaseStorage = new UploadController();
            $imageUrl = $firebaseStorage->getImage($user->image);

            // $user = User::find($request->user_id);
            $user['image'] = $imageUrl;
            $user['phone_number'] = $request->phone_number;
            $user['fullname'] = $request->fullname;

            $data = [
                'res' => 'done',
                'msg' => 'Cập nhập thành công',
                'data' => $user,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $e->getMessage(),
            ]);
        }
    }

    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([
                'res' => 'error',
                'mes' => 'Vui lòng chọn file',
                'data' => [],
            ]);
        }

        try {
            $image = $request->file('file');
            $firebaseStorage = new UploadController();
            $firebase_storage_path = 'images/';

            $downloadUrl = $firebaseStorage->upload($image, $firebase_storage_path);

            return response()->json([
                'res' => 'done',
                'msg' => '',
                'data' => $downloadUrl,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'mes' => 'Upload thất bại.',
                'data' => $e->getMessage(),
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Cập nhập thất bại',
                'data' => [],
            ]);
        }
        try {
            $user = User::where('u_id', $request->u_id)->first();
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'res' => 'error',
                    'msg' => 'Mật khẩu không đúng',
                    'data' => [],
                ]);
            }

            $this->auth->changeUserPassword($request->u_id, $request->new_password);

            $password = Hash::make($request->new_password);
            $user->password = $password;
            $user->save();

            return response()->json([
                'res' => 'done',
                'msg' => 'Cập nhật thành công',
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'msg' => $e->getMessage(),
                'data' => [],
            ], 200);
        }
    }

    public function changeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_id' => ['required'],
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Cập nhập thất bại',
                'data' => [],
            ]);
        }
        try {
            $this->auth->changeUserEmail($request->u_id, $request->email);
            return response()->json([
                'res' => 'done',
                'msg' => '',
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $e->getMessage(),
            ], 200);
        }
    }

    public function verificationEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Cập nhập thất bại',
                'data' => [],
            ]);
        }
        try {
            // $link = $this->auth->getEmailVerificationLink($request->email);
            $this->auth->sendEmailVerificationLink($request->email);
            return response()->json([
                'res' => 'done',
                'msg' => '',
                'data' => [
                    // 'link' => $link,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $e->getMessage(),
            ], 200);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Cập nhập thất bại',
                'data' => [],
            ]);
        }
        try {
            // $link = $this->auth->getPasswordResetLink($request->email);
            $this->auth->sendPasswordResetLink($request->email);
            return response()->json([
                'res' => 'done',
                'msg' => '',
                'data' => [
                    // 'link' => $link,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $e->getMessage(),
            ], 200);
        }
    }
}
