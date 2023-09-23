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
class MoneyController extends Controller {
    public function __invoke():JsonResponse {
        return response()->json([
            'money' => Misc::accumulatedMoney()
        ], Response::HTTP_OK);
    }
}