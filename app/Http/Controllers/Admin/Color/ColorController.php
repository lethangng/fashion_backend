<?php

namespace App\Http\Controllers\Admin\Color;

use App\Models\Color;
use Illuminate\View\View;
use App\Http\Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ColorController extends Controller
{
    public function index($page = 1): View
    {
        $colors = Color::latest()->paginate(20, ['*'], 'page', $page);
        $total_pages = $colors->lastPage();
        $current_page = $colors->currentPage();

        return view('admin.colors.index', compact('colors', 'total_pages', 'current_page'));
    }

    public function create(): View
    {
        return view('admin.colors.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'color' => 'required',
        ], [
            'name.required' => 'Vui lòng nhập tên màu sắc.',
            'color.required' => 'Vui lòng chọn màu sắc.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            $color = "";
            if (Str::startsWith($request->color, 'rgba(')) {
                // $rgba = "rgba(118, 165, 175, 0.611)";
                preg_match_all("/([\\d.]+)/", $request->color, $matches);
                $color = sprintf("#%02x%02x%02x%02x", $matches[1][0], $matches[1][1], $matches[1][2], $matches[1][3] * 255);
            } else {
                $color = Helper::colorNameToHex($request->color) . 'FF';
            }

            // dd($color);

            $request['color'] = $color;
            $color = Color::create($request->all());

            if ($color instanceof Color) {
                toastr()->success('Thêm màu sắc mới thành công!');

                return redirect()->route('color.index');
            }

            toastr()->error('Thêm màu sắc mới thất bại.');

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
            toastr()->error('Lấy thông tin màu sắc thất bại.');
            return back();
        } else {
            $color = Color::find($id);
            return view('admin.colors.edit', compact('color'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'color' => 'required',
        ]);

        $color = Color::find($request->id);

        $color_value = "";
        if (Str::startsWith($request->color, 'rgba(')) {
            preg_match_all("/([\\d.]+)/", $request->color, $matches);
            $color_value = sprintf("#%02x%02x%02x%02x", $matches[1][0], $matches[1][1], $matches[1][2], $matches[1][3] * 255);
        } else {
            $color_value = Helper::colorNameToHex($request->color) . 'FF';
        }

        $request['color'] = $color_value;

        $color->update($request->all());

        toastr()->success('Sửa thành công!');
        return redirect()->route('color.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (request()->ajax()) {
            $ids = $request->id;
            Color::whereIn('id', $ids)->delete();
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
            $color = Color::find($request->id);

            $result = $color->delete();
            if (is_bool($result)) {
                toastr()->success('Xóa thành công!');
            } else {
                toastr()->error('Xóa thất bại.');
            }
            return redirect()->route('color.index');
        }
    }

    public function search(Request $request)
    {
        $search = trim($request->search);
        $search = preg_replace('/\s+/', ' ', $search);
        $colors = Color::where('name', 'like', '%' . $search . '%')->paginate(10);

        return view('admin.colors.index', compact('colors', 'search'))->with('i', (request()->input('page', 1) - 1) * 10);
    }
}
