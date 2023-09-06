<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ExemplarResource;
use App\Models\Exemplar;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ExemplarController extends Controller {
    public function index(int $book_id, Request $request):AnonymousResourceCollection|JsonResponse|Response {
        $request->merge([ 'book_id' => $book_id ]);
        $validator = Validator::make($request->all(), [
            'book_id' => [ 'required', 'integer', 'min:1', 'exists:books,id' ]
        ]);
        if ($validator->fails()) {
            Misc::monitor('get',Response::HTTP_NOT_FOUND);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_NOT_FOUND);
        }
        $exemplars = Exemplar::with('book')->where('book_id',$validator->validated('book_id'))->get();
        if ($exemplars->isEmpty()) {
            Misc::monitor('get',Response::HTTP_NO_CONTENT);
            return response()->noContent();
        } else {
            Misc::monitor('get',Response::HTTP_OK);
            return ExemplarResource::collection($exemplars);
        }
    }

    public function show(Exemplar $exemplar):ExemplarResource {
        Misc::monitor('get',Response::HTTP_OK);
        return ExemplarResource::make($exemplar);
    }

}