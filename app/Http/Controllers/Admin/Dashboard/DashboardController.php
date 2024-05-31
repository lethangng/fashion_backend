<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\User\UserController;

class DashboardController extends Controller
{
    public function index()
    {
        // $user = Auth::user();
        // // dd($user);
        // $user_name = $user->fullname;
        // $user_id = $user->id;

        $product_count = Product::count();
        $order_count = Order::count();
        $user_count = (new UserController())->totalUser();

        return view('admin.index', compact('product_count', 'order_count', 'user_count'));
    }
}
