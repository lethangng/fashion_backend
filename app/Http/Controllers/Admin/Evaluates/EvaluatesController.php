<?php

namespace App\Http\Controllers\Admin\Evaluates;

use App\Models\User;
use App\Models\Product;
use App\Models\Evaluate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use Illuminate\Validation\ValidationException;

class EvaluatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1)
    {
        $evaluates = Evaluate::latest()->paginate(20, ['*'], 'page', $page);
        $total_pages = $evaluates->lastPage();
        $current_page = $evaluates->currentPage();

        $evaluates = $evaluates->map(function ($evaluate) {
            $user_name = User::find($evaluate->user_id)->fullname;
            $product = Product::find($evaluate->product_id);
            $imageUrl = (new UploadController())->getImage($product->image);

            return [
                'id' => $evaluate->id,
                'user_name' => $user_name,
                'product_name' => $product->name,
                'product_image' => $imageUrl,
                'star_number' => $evaluate->star_number,
                'content' => $evaluate->content,
                'created_at' => $evaluate->created_at,
                'status' => $evaluate->status,
            ];
        });

        return view('admin.evaluates.index', compact('evaluates', 'total_pages', 'current_page'));
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
        // dd($id);
        // if (!$id) {
        //     toastr()->error('Lấy thông tin thất bại.');
        //     return back();
        // } else {
        //     $evaluate = Evaluate::find($id);
        //     return view('admin.evaluates.edit', compact('evaluate'));
        // }
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
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        try {
            $evaluate = Evaluate::find($request->id);
            $evaluate->status = $request->status;
            $evaluate->save();

            toastr()->success('Cập nhập thành công!');
            return redirect()->route('evaluate.index');
        } catch (\Exception $e) {
            dd($e);
            toastr()->error('Cập nhập thất bại.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (request()->ajax()) {
            $ids = $request->id;
            Evaluate::whereIn('id', $ids)->delete();
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
                $evaluate = Evaluate::find($request->id);
                $evaluate->delete();
                toastr()->success('Xóa thành công!');
                return redirect()->route('evaluate.index');
            } catch (\Exception $e) {
                dd($e);
                toastr()->error('Xóa thất bại.');
            }
        }
    }
}
