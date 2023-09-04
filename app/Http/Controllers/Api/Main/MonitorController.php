<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\Misc;

class MonitorController extends Controller {
    public function __invoke():JsonResponse {
        return response()->json([
            Misc::LIST_METHOD => Misc::list_method(),
            Misc::LIST_STATUS => Misc::list_status(),
            Misc::LIST_URL => Misc::list_url()
        ], Response::HTTP_OK);
    }
}