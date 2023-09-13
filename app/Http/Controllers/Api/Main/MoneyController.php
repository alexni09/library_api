<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\Misc;

class MoneyController extends Controller {
    public function __invoke():JsonResponse {
        return response()->json([
            'money' => Misc::accumulatedMoney()
        ], Response::HTTP_OK);
    }
}