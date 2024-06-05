<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Order\OrderController;
use App\Http\Controllers\Admin\Product\ProductController;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // $user = Auth::user();
        // // dd($user);
        // $user_name = $user->fullname;
        // $user_id = $user->id;
        // dd($request->all());

        $product_count = Product::count();
        $order_count = Order::count();
        $user_count = (new UserController())->totalUser();

        $year = $request->year ?? date('Y');
        $thong_ke_product = (new ProductController)->statistical($year);
        $thong_ke_order = (new OrderController)->statistical($year);
        // $thong_ke_user = (new UserController)->statistical($year);

        // dd($thong_ke_product);

        return view('admin.index', compact('product_count', 'order_count', 'user_count', 'thong_ke_product', 'thong_ke_order', 'year'));
    }
}
