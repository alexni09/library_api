<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\Misc;
use Illuminate\Http\JsonResponse;

class CountController extends Controller {
    public function __invoke(Request $request):JsonResponse {
        $exc = DB::table('exemplars')->selectRaw('count(distinct id) as qty')->get()[0]->qty;
        Misc::monitor('get',Response::HTTP_OK);
        return response()->json([
            'data' => [
                'exemplar_count' => $exc
            ]
        ], Response::HTTP_OK);
    }
}