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
use Illuminate\Database\Eloquent\Builder;

class ExemplarController extends Controller {
    public function index(int $book_id, Request $request):AnonymousResourceCollection|JsonResponse|Response {
        $request->merge([ 'book_id' => $book_id ]);
        $validator = Validator::make($request->all(), [
            'book_id' => [ 'required', 'integer', 'min:1', 'exists:books,id' ],
            'condition' => [ 'nullable', 'integer', 'min:1', 'max:4' ],
            'borrowable' => [ 'nullable', 'boolean' ]  /* ?borrowable=1   ?borrowable=0 */
        ]);
        if ($validator->fails()) {
            Misc::monitor('get',Response::HTTP_NOT_FOUND);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_NOT_FOUND);
        }
        $condition = isset($request['condition']) ? intval($request['condition']) : null;
        $borrowable = isset($request['borrowable']) ? boolval($request['borrowable']) : true;
        $exemplars = Exemplar::with('book')->where('book_id',$validator->validated('book_id'))->when($condition, function(Builder $query1, int $condition) {
            $query1->where('condition','<=',$condition);
        })->when($borrowable, function(Builder $query2, bool $borrowable) {
            $query2->where('borrowable',$borrowable);
        })->get();
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