<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\Misc;
/**
 * @group Frontend
 */
class MonitorController extends Controller {
    public function __invoke():JsonResponse {
        $c = count(Misc::list_method());
        $arr = [];
        for($i = 0; $i < $c; $i++) $arr[] = [
            'id' => $i,
            'datetime' => Misc::list_datetime()[$i],
            'ip' => Misc::list_ip()[$i],
            'method' => Misc::list_method()[$i],
            'status' => intval(Misc::list_status()[$i]),
            'url' => Misc::list_url()[$i]
        ];
        return response()->json([
            'data' => $arr
        ], Response::HTTP_OK);
    }
}