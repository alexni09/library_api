<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Exemplar;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BorrowResource;
//use Illuminate\Database\Eloquent\Builder;

class BorrowController extends Controller {
    public function borrow(Exemplar $exemplar):BorrowResource|JsonResponse {
        if (!$exemplar->borrowable) {
            Misc::monitor('post',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => 'This exemplar cannot leave the library.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}