<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $brands = Brand::latest()->select(['id', 'name'])->get();

        // $data = [
        //     'res' => 'done',
        //     'msg' => '',
        //     'data' => $brands,
        // ];

        // return response()->json($data, 200);

        // $page = $request->page ?? 1;
        // $limit = $request->limit ?? 6;

        $brands = Brand::latest()->select(['id', 'name'])->get();
        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $brands,
        ];

        return response()->json($data, 200);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
