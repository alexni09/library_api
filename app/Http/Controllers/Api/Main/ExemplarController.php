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

    public function store(Request $request):ExemplarResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'book_id' => [ 'required', 'integer', 'min:1', 'exists:books,id' ],
            'condition' => [ 'required', 'integer', 'min:1', 'max:4' ],
            'borrowable' => [ 'nullable', 'boolean' ]  /* ?borrowable=1   ?borrowable=0 */
        ]);
        if ($validator->fails()) {
            Misc::monitor('post',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $borrowable = isset($request['borrowable']) ? boolval($request['borrowable']) : true;
        $exemplar = Exemplar::create([
            'book_id' => $request['book_id'],
            'condition' => $request['condition'],
            'borrowable' => $borrowable
        ]);
        Misc::monitor('post',Response::HTTP_CREATED);
        return ExemplarResource::make($exemplar);
    }

    public function update(Request $request, Exemplar $exemplar):ExemplarResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'book_id' => [ 'nullable', 'required_without_all:condition,borrowable,change_donor', 'integer', 'min:1', 'exists:books,id' ],
            'condition' => [ 'nullable', 'required_without_all:book_id,borrowable,change_donor', 'integer', 'min:1', 'max:4' ],
            'borrowable' => [ 'nullable', 'required_without_all:book_id,condition,change_donor', 'boolean' ],  /* ?borrowable=1   ?borrowable=0 */
            'user_id' => [ 'nullable', 'required_if:change_donor,true', 'integer', 'min:1', 'exists:users,id' ],
            'change_donor' => [ 'nullable', 'boolean' ]
        ]);
        if ($validator->fails()) {
            Misc::monitor('put',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $book_id = $validator->validated()['book_id'] ?? null;
        $condition = $validator->validated()['condition'] ?? null;
        $borrowable = $validator->validated()['borrowable'] ?? null;
        $user_id = $validator->validated()['user_id'] ?? null;
        $change_donor = $validator->validated()['change_donor'] ?? null;
        if (isset($book_id)) $exemplar->book_id = $book_id;
        if (isset($condition)) $exemplar->condition = $condition;
        if (isset($borrowable)) $exemplar->borrowable = $borrowable;
        if ($change_donor == 1) $exemplar->user_id = intval($user_id);
        else if ($change_donor == 0) $exemplar->user_id = null;
        $exemplar->save();
        Misc::monitor('put',Response::HTTP_OK);
        return ExemplarResource::make($exemplar);
    }

}