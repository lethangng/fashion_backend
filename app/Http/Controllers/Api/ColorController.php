<?php

namespace App\Http\Controllers\Api;

use App\Models\Color;
use App\Http\Helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::latest()->select(['id', 'name', 'color'])->get();

        $colors = $colors->map(function ($color) {
            $color_value = Helper::convertColor($color->color);
            return [
                'id' => $color->id,
                'name' => $color->name,
                'color' => $color_value,
            ];
        });

        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $colors,
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
