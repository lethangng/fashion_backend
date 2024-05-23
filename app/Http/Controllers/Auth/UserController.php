<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UserController extends Controller
{
    private $firebaseAuth;

    public function __construct()
    {
        $this->firebaseAuth = Firebase::auth();
    }

    public function updateInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_id' => 'required',
            'fullname' => 'required|min:10|max:30',
            'phone_number' => 'required|size:10'
        ]);
        $data = [
            'res' => 'error',
            'msg' => 'Cập nhập thất bại',
            'data' => [],
        ];

        if ($validator->fails()) {
            return response()->json($data);
        } else {
            try {
                $userProperties = [
                    'phoneNumber' => '+84' . substr($request->phone_number, 1),
                    'displayName' => $request->fullname,
                ];

                $this->firebaseAuth->updateUser($request->u_id, $userProperties);

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
                return response()->json($data);
            }
        }
    }
}
