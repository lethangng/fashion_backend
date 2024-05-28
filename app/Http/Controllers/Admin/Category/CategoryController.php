<?php

namespace App\Http\Controllers\Admin\Category;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index($page = 1): View
    {
        $categories = Category::latest()->paginate(20, ['*'], 'page', $page);
        $total_pages = $categories->lastPage();
        $current_page = $categories->currentPage();

        return view('admin.category.index', compact('categories', 'total_pages', 'current_page'));
    }

    public function create(): View
    {
        return view('admin.category.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'description' => 'required'
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            // 'description.required' => 'Vui lòng nhập mô tả.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            // $category = new Category($request->all());
            // $category->save();
            $category = Category::create($request->all());

            if ($category instanceof Category) {
                toastr()->success('Thêm danh mục mới thành công!');

                return redirect()->route('category.index');
            }

            toastr()->error('Thêm danh mục mới thất bại.');

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // dd($id);
        if (!$id) {
            toastr()->error('Lấy thông tin danh mục thất bại.');
            return back();
        } else {
            $category = Category::find($id);
            return view('admin.category.edit', compact('category'));
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
        ]);

        $category = Category::find($request->id);
        $category->update($request->all());

        toastr()->success('Sửa thành công!');
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (request()->ajax()) {
            $ids = $request->id;
            Category::whereIn('id', $ids)->delete();
            toastr()->success('Xóa thành công!');
            return response()->json($request->id);
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            toastr()->error('Thêm danh mục mới thất bại.');
            throw new ValidationException($validator);
        } else {
            $category = Category::find($request->id);

            $result = $category->delete();
            if (is_bool($result)) {
                toastr()->success('Xóa thành công!');
            } else {
                toastr()->error('Xóa thất bại.');
            }
            return redirect()->route('category.index');
        }
    }

    public function search(Request $request)
    {

        $search = trim($request->search);
        $search = preg_replace('/\s+/', ' ', $search);
        $categories = Category::where('name', 'like', '%' . $search . '%')->paginate(10);

        return view('admin.category.index', compact('categories', 'search'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public static function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }
}
