<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Evaluate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;

class EvaluatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 2;
        $product_id = $request->product_id;
        $is_sum = $request->is_sum;

        $sum = 0;
        $star_1 = 0;
        $star_2 = 0;
        $star_3 = 0;
        $star_4 = 0;
        $star_5 = 0;
        $count = 0;
        $average_evaluate = 0;

        if ($page == 1 && $is_sum == 1) {
            $evaluate = Evaluate::where('product_id', $product_id)->where('status', 1)->get();
            $count = $evaluate->count();

            foreach ($evaluate as $item) {
                switch ($item->star_number) {
                    case 1:
                        $star_1 += 1;
                        break;
                    case 2:
                        $star_2 += 1;
                        break;
                    case 3:
                        $star_3 += 1;
                        break;
                    case 4:
                        $star_4 += 1;
                        break;
                    case 5:
                        $star_5 += 1;
                        break;
                    default:
                        // code block
                }
                $sum += $item->star_number;
            }

            $average_evaluate = $count == 0 ? 0 : round(($sum / $count), 1);
        }

        $firebaseStorage = new UploadController();

        $evaluates = Evaluate::where('product_id', $product_id)->where('status', 1)->latest()->paginate($limit, ['*'], 'page', $page);

        $evaluates = $evaluates->map(function ($evaluate) use ($firebaseStorage) {
            $user = User::find($evaluate->user_id);
            $imageUrl = $firebaseStorage->getImage($user->image);

            return [
                'id' => $evaluate->id,
                'fullname' => $user->fullname,
                'star_number' => $evaluate->star_number,
                'content' => $evaluate->content,
                'image_url' => $imageUrl,
                'created_at' => date('d/m/Y', strtotime($evaluate->created_at)),
            ];
        });

        $data_1 = [
            'res' => 'done',
            'msg' => '',
            'data' => $evaluates,
        ];

        $data_2 = [
            'res' => 'done',
            'msg' => '',
            'data' => [
                'average_evaluate' => $average_evaluate,
                'count' => $count,
                'star_1' => $star_1,
                'star_2' => $star_2,
                'star_3' => $star_3,
                'star_4' => $star_4,
                'star_5' => $star_5,
                'evaluates' => $evaluates,
            ],
        ];

        return response()->json($is_sum == 1 ? $data_2 : $data_1, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
            'star_number' => 'required',
            // 'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => 'error',
                'msg' => '',
                'data' => $validator->errors(),
            ]);
        } else {
            try {
                $request['status'] = 0;
                $evaluate = Evaluate::create($request->all());
                return response()->json([
                    'res' => 'done',
                    'msg' => 'Thành công',
                    'data' => $evaluate,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'res' => 'error',
                    'msg' => '',
                    'data' => $e->getMessage(),
                ]);
            }
        }
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

    public static function countStar($productId)
    {
        $evaluate = Evaluate::where('product_id', $productId)->where('status', 1)->get();
        $count = $evaluate->count();
        if ($count == 0) {
            return [
                'count_evaluate' => 0,
                'average_evaluate' => 0,
            ];
        }

        $sum = 0;

        foreach ($evaluate as $item) {
            $sum += $item->star_number;
        }

        return [
            'count_evaluate' => $count,
            'average_evaluate' => floor($sum / $count),
        ];
    }
}
