<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
            'u_id' => 'required',
            'fullname' => 'required|min:10|max:30',
            'phone_number' => 'required|size:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Cập nhập thất bại',
                'data' => [],
            ], 200);
        }

        try {
            $userProperties = [
                'phoneNumber' => '+84' . substr($request->phone_number, 1),
                'displayName' => $request->fullname,
            ];

            $this->auth->updateUser($request->u_id, $userProperties);

            $user = User::where('u_id', $request->u_id)->first();
            $user->update([
                'phone_number' => $request->phone_number,
                'fullname' => $request->fullname,
            ]);

            $data = [
                'res' => 'done',
                'msg' => 'Cập nhập thành công',
                'data' => $request->all(),
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
