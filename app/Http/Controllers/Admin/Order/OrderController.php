<?php

namespace App\Http\Controllers\Admin\Order;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\NotificationController;
use App\Http\Helper\Helper;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1)
    {
        $orders = Order::latest()->paginate(20, ['*'], 'page', $page);
        $total_pages = $orders->lastPage();
        $current_page = $orders->currentPage();

        $orders = $orders->map(function ($order) {
            $user = User::find($order->user_id);

            return [
                'id' => $order->id,
                'user_name' => $user->fullname,
                'user_id' => $user->id,
                'total_price' => $order->total_price - $order->price_off,
                'status' => $order->status,
                'created_at' => $order->created_at,
            ];
        });

        return view('admin.orders.index', compact('orders', 'total_pages', 'current_page'));
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
        if (!$id) {
            toastr()->error('Lấy thông tin thất bại.');
            return back();
        } else {
            $order = Order::find($id);
            $user = User::find($order->user_id);

            $firebaseStorage = new UploadController();

            $order_products = OrderProduct::where('order_id', $order->id)->get();
            $order_products = $order_products->map(function ($order_product) use ($firebaseStorage) {
                $product = Product::find($order_product->product_id);
                $imageUrl = $firebaseStorage->getImage($product->image);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $order_product->price,
                    'image_url' => $imageUrl,
                    'quantity' => $order_product->quantity,
                    'total_price' => $order_product->quantity * $order_product->price,
                ];
            });

            return view('admin.orders.edit', compact('order', 'order_products', 'user'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            try {
                $order = Order::find($request->id);
                $order->status = $request->status;
                $order->save();
                // $order->update($request->all());

                $firebaseStorage = new UploadController();

                $user_device_token = User::find($order->user_id)->device_token;

                // dd($imageUrl);
                if ($user_device_token) {
                    $order_product = OrderProduct::where('order_id', $id)->first();
                    $product = Product::find($order_product->product_id);
                    $imageUrl = $firebaseStorage->getImage($product->image);

                    $message = Helper::statusTitle($request->status);

                    $notify = new NotificationController();
                    $notify->sendMessage($user_device_token, 'Thông báo', 'Đơn hàng của bạn đã chuyển sang trạng thái ' . $message, $imageUrl);
                }

                toastr()->success('Cập nhập thành công!');
                return redirect()->route('order.edit', $request->id);
            } catch (\Exception $e) {
                // dd($e);
                toastr()->error('Cập nhâp thất bại.');
                // return back();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (request()->ajax()) {
            $ids = $request->id;
            Order::whereIn('id', $ids)->delete();
            toastr()->success('Xóa thành công!');
            return response()->json($request->id);
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            toastr()->error('Thiếu thông tin.');
            throw new ValidationException($validator);
        } else {
            try {
                $order = Order::find($request->id);
                $order->delete();
                toastr()->success('Xóa thành công!');
                return redirect()->route('order.index');
            } catch (\Exception $e) {
                dd($e);
                toastr()->error('Xóa thất bại.');
            }
        }
    }

    public function statistical(int $year)
    {
        $thong_ke = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = Order::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
            $thong_ke[] = $count;
        }

        return $thong_ke;
    }
}
