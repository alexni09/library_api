<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ExemplarResource;
use App\Models\Exemplar;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ExemplarDonationController extends Controller {
    public function __invoke(Request $request):ExemplarResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'book_id' => [ 'required', 'integer', 'min:1', 'exists:books,id' ],
            'condition' => [ 'required', 'integer', 'min:1', 'max:4' ]        
        ]);
        if ($validator->fails()) {
            Misc::monitor('post',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $exemplar = Exemplar::create([
            'book_id' => $request['book_id'],
            'condition' => $request['condition'],
            'borrowable' => true,
            'user_id' => $request->id
        ]);
        Misc::monitor('post',Response::HTTP_CREATED);
        return ExemplarResource::make($exemplar);
    }
}