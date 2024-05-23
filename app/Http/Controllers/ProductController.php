<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $data = [
            'res' => 'done',
            'msg' => '',
            'data' => $products,
            
        ];
        return response()->json($data, 200);
    }

    public function upload(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required'
        ]);


        if ($validator->fails()) {
            $data = [
                'res' => 'error',
                'msg' => $validator->messages(),
                'data' => [],
            ];
            return response()->json($data, 200);
        } else {
            $product = new Product;
            $product-> name = $request->name;
            $product-> price = $request->price;

            $product->save();
            $data = [
                'res' => 'done',
                'msg' => 'Upload data success',
                'data' => [],
            ];
            return response()->json($data, 200);
        }
    }

    public function edit(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required'
        ]);


        if ($validator->fails()) {
            $data = [
                'res' => 'error',
                'msg' => $validator->messages(),
                'data' => [],
            ];
            return response()->json($data, 200);
        } else {
            $product = Product::find($id);
            $product-> name = $request->name;
            $product-> price = $request->price;

            $product->save();
            $data = [
                'res' => 'done',
                'msg' => 'Edit data success',
                'data' => [],
            ];
            return response()->json($data, 200);
        }
    }

    public function delete($id) {
        $product = Product::find($id);
        $product->delete();

        $data = [
            'res' => 'done',
            'msg' => 'Delete success',
            'data' => [],
        ];
        return response()->json($data, 200);
    }
}
