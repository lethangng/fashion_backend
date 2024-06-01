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
    public function index()
    {
        // $user = Auth::user();
        // // dd($user);
        // $user_name = $user->fullname;
        // $user_id = $user->id;

        $product_count = Product::count();
        $order_count = Order::count();
        $user_count = (new UserController())->totalUser();

        $thong_ke_product = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = (new ProductController)->statistical($i);
            $thong_ke_product[] = $count;
        }

        $thong_ke_order = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = (new OrderController)->statistical($i);
            $thong_ke_order[] = $count;
        }
        // dd($thong_ke_product);

        return view('admin.index', compact('product_count', 'order_count', 'user_count', 'thong_ke_product', 'thong_ke_order'));
    }
}
