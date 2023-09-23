<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\Misc;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
/**
 * @group Frontend
 */
class CountController extends Controller {
    public function __invoke(Request $request):JsonResponse {
        $count_category = Redis::get('count_category');
        $count_book = Redis::get('count_book');
        $count_exemplar = Redis::get('count_exemplar');
        $count_mysql = Redis::get('count_mysql');
        return response()->json([
            'data' => [
                'category_count' => $count_category,
                'book_count' => $count_book,
                'exemplar_count' => $count_exemplar,
                'mysql_count' => $count_mysql
            ]
        ], Response::HTTP_OK);
    }
}