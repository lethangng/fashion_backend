<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;

class RegisterController extends Controller
{
    private $auth;
    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    public function register(Request $request)
    {
        // return response()->json(substr($request->phone_number, 1));
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|min:10|max:30',
            'email' => 'required|email',
            'password' => 'required|min:6|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Đăng ký thất bại',
                'data' => [],
            ], 200);
        }
        try {
            $userProperties = [
                'email' => $request->email,
                'password' => $request->password,
                'displayName' => $request->fullname,
            ];

            $createdUser = $this->auth->createUser($userProperties);

            $request['u_id'] = $createdUser->uid;
            // $password = Hash::make($request->password);
            // $request['password'] = $password;
            $request['role'] = 1;
            $request['login_type'] = "password";

            User::create($request->all());

            $data = [
                'res' => 'done',
                'msg' => 'Đăng ký thành công',
                'data' => $request->all(),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // Dành riêng cho login bằng facebook và google
    public function checkLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_id' => 'required',
            'email' => 'required|email',
            'login_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => 'Đăng nhập thất bại',
                'data' => [],
            ], 200);
        }

        try {
            // $login_type = $request->login_type;
            $u_id = $request->u_id;

            // $user = User::where('u_id', $u_id)->where('login_type', $login_type)->first();
            $user = User::where('u_id', $u_id)->first();
            if ($user) {
                $data = [
                    'res' => 'done',
                    'msg' => 'Cho phép đăng nhập',
                    'data' => [],
                ];
                return response()->json($data);
            } else {
                User::create($request->all());

                $data = [
                    'res' => 'done',
                    'msg' => 'Đăng ký thành công',
                    'data' => $request->all(),
                ];

                return response()->json($data);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
