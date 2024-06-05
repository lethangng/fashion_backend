<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    private $auth;

    public function __construct()
    {
        // $this->auth = Firebase::auth();
        $this->auth = app('firebase.auth');;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = $this->auth->getUserInfo();
        // dd($user);
        $user = Auth::user();
        // dd($user);
        // $user_name = $user->fullname;
        // $user_id = $user->id;
        // dd($request->all());
        return view('admin.auth.profile', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'u_id' => 'required',
            'email' => 'required|email',
            'fullname' => 'required',
            'confirm-password' => 'same:new_password',
            // 'new-password' => 'min:6'
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Vui lòng nhập đúng định dạng email.',
            'fullname.required' => 'Vui lòng nhập họ và tên.',
            'confirm-password.same' => 'Mật khẩu nhập lại không đúng.',
            // 'new-password.min' => 'Vui lòng nhập tối thiểu 6 ký tự.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            try {
                $user = User::find($request->id);
                $uid = $request->u_id;

                if ($request->email != Auth::user()->email) {
                    $this->auth->changeUserEmail($uid, $request->email);
                }

                $password = Hash::make($request->new_password);

                if ($request->new_password != null && $password == $user->password) {
                    // $this->auth->changeUserPassword($uid, $request->new_password);
                    $user->password = $password;
                }

                // $properties = [
                //     'displayName' => $request->fullname,
                //     'phoneNumber' => $request->phone_number,
                // ];
                // $updatedUser = $this->auth->updateUser($uid, $properties);


                $user->fullname = $request->fullname;
                $user->phone_number = $request->phone_number;
                $user->email = $request->email;
                $user->save();

                toastr()->success('Thành công!');
                return redirect()->route('profile.index');
            } catch (\Exception $e) {
                // dd($e);
                toastr()->error('Thất bại.');
                return redirect()->route('profile.index');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
