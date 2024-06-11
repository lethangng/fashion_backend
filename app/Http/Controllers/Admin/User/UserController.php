<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth\UserQuery;
// use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
// use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Http\Controllers\UploadController;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class UserController extends Controller
{
    private $firebaseAuth;

    public function __construct()
    {
        // $this->firebaseAuth = Firebase::auth();
        $this->firebaseAuth = app('firebase.auth');;
    }

    public function totalUser()
    {
        $users = $this->firebaseAuth->listUsers();
        return iterator_count($users);
    }

    public function index($page = 1)
    {
        // $userQuery = [
        //     'sortBy' => UserQuery::FIELD_CREATED_AT,
        //     'order' => UserQuery::ORDER_DESC,
        //     // 'order' => UserQuery::ORDER_DESC # this is the default
        //     'offset' => 0,
        //     'limit' => 20, # The maximum supported limit is 500
        // ];

        // $users = $this->firebaseAuth->queryUsers($userQuery);
        // // dd($users);

        // $users = collect($users)->values()->map(function ($user) {
        //     return (object) [
        //         'uid' => $user->uid,
        //         'fullname' => $user->displayName,
        //         'email' =>  $user->email ?? $user->providerData[0]->email ?? $user->providerData[1]->email,
        //         'phone_nummber' => $user->phoneNumber,
        //         'disabled' => $user->disabled,
        //         // 'emailVerified' => $user->emailVerified,
        //         'providerId' => $user->providerData[0]->providerId,
        //     ];
        // })->toArray();


        // return view('admin.users.index', compact('users'));

        $users = User::latest()->paginate(20, ['*'], 'page', $page);

        $total_pages = $users->lastPage();
        $current_page = $users->currentPage();

        // dd($current_page);

        $firebaseStorage = new UploadController();

        $users = $users->map(function ($user) use ($firebaseStorage) {
            $imageUrl = $firebaseStorage->getImage($user->image);
            // dd($user);

            return [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'login_type' => $user->login_type,
                'u_id' => $user->u_id,
                'image_url' => $imageUrl,
                'status' => $user->status,
            ];
        });

        return view('admin.users.index', compact('users', 'total_pages', 'current_page'));
    }

    public function destroy(Request $request)
    {
        // dd($request->all());

        if (request()->ajax()) {
            try {
                $u_ids = $request->id;
                $forceDeleteEnabledUsers = true;
                $result = $this->firebaseAuth->deleteUsers($u_ids, $forceDeleteEnabledUsers);
                if ($result->successCount()) {
                    toastr()->success('Xóa thành công!');
                    return response()->json($request->id);
                } else {
                    toastr()->error('Xóa thất bại.');
                }
            } catch (\Exception $e) {
                dd($e);
            }
        }

        $u_id = $request->u_id;
        try {
            $this->firebaseAuth->deleteUser($u_id);
            toastr()->success('Xóa thành công!');
        } catch (UserNotFound $e) {
            dd($e->getMessage());
        } catch (AuthException $e) {
            dd('Deleting');
        }

        return redirect()->route('user.index');
    }

    public function disable(Request $request)
    {
        $u_id = $request->u_id;
        $type = $request->type;
        try {
            if ($type == 'Disable') {
                $updatedUser = $this->firebaseAuth->disableUser($u_id);
                // if ($updatedUser) {}
                toastr()->success('Chặn thành công!');
            } else if ($type == 'Enable') {
                $updatedUser = $this->firebaseAuth->enableUser($u_id);
                // if ($updatedUser) {}
                toastr()->success('Bỏ chặn thành công!');
            }
        } catch (UserNotFound $e) {
            dd($e->getMessage());
        } catch (AuthException $e) {
            dd('Deleting');
        }

        return redirect()->route('user.index');
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $userQuery = [
            'sortBy' => UserQuery::FIELD_CREATED_AT,
            'order' => UserQuery::ORDER_DESC,
            // 'order' => UserQuery::ORDER_DESC # this is the default
            'offset' => 0,
            'limit' => 20, # The maximum supported limit is 500
            'filter' => [UserQuery::FILTER_EMAIL => $search],
        ];

        $users = $this->firebaseAuth->queryUsers($userQuery);
        // dd($users);

        $users = collect($users)->values()->map(function ($user) {
            return (object) [
                'uid' => $user->uid,
                'fullname' => $user->displayName,
                'email' => $user->providerData[0]->email,
                'phone_nummber' => $user->phoneNumber,
                'disabled' => $user->disabled,
                // 'emailVerified' => $user->emailVerified,
                'providerId' => $user->providerData[0]->providerId,
            ];
        })->toArray();


        return view('admin.users.index', compact('users', 'search'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function statistical(int $year)
    {
        $thong_ke = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = User::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
            $thong_ke[] = $count;
        }

        return $thong_ke;
    }
}
