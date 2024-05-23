<?php

namespace App\Http\Controllers\Admin\Size;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SizeController extends Controller
{
    public function index(): View
    {
        $sizes = Size::latest()->paginate(10);

        return view('admin.sizes.index', compact('sizes'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create(): View
    {
        return view('admin.sizes.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required',
        ], [
            'size.required' => 'Vui lòng nhập kích thước.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            // $size = new Size($request->all());
            // $size->save();
            $size = Size::create($request->all());

            if ($size instanceof Size) {
                toastr()->success('Thêm kích thước mới thành công!');

                return redirect()->route('size.index');
            }

            toastr()->error('Thêm kích thước mới thất bại.');

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        // dd($id);
        if (!$id) {
            toastr()->error('Lấy thông tin kích thước thất bại.');
            return back();
        } else {
            $size = Size::find($id);
            return view('admin.sizes.edit', compact('size'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required',
            // 'name' => 'required',
            'size' => 'required'
        ]);

        $size = Size::find($request->id);
        $size->update($request->all());

        toastr()->success('Sửa thành công!');
        return redirect()->route('size.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (request()->ajax()) {
            $ids = $request->id;
            Size::whereIn('id', $ids)->delete();
            toastr()->success('Xóa thành công!');
            return response()->json($request->id);
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            toastr()->error('Xóa thất bại.');
            throw new ValidationException($validator);
        } else {
            $size = Size::find($request->id);

            $result = $size->delete();
            if (is_bool($result)) {
                toastr()->success('Xóa thành công!');
            } else {
                toastr()->error('Xóa thất bại.');
            }
            return redirect()->route('size.index');
        }
    }

    public function search(Request $request)
    {
        $search = trim($request->search);
        $search = preg_replace('/\s+/', ' ', $search);
        $sizes = Size::where('name', 'like', '%' . $search . '%')->paginate(10);

        return view('admin.sizes.index', compact('sizes', 'search'))->with('i', (request()->input('page', 1) - 1) * 10);
    }
}
