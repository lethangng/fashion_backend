<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function userInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_id' => 'required',
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

            $validator = Validator::make(['image' => $user->image], [
                'image' => 'url',
            ]);

            // Kiểm tra xem image lưu trong CSDL có phải là 1 URL không
            if ($validator->fails()) {
                $firebaseStorage = new UploadController();
                $imageUrl = $firebaseStorage->getImage($user->image);
                $user['image'] = $imageUrl;
            }

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
            'u_id' => ['required'],
            'password' => ['required', 'min:6', 'max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Cập nhập thất bại',
                'data' => [],
            ]);
        }
        try {
            $this->auth->changeUserPassword($request->u_id, $request->password);
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
