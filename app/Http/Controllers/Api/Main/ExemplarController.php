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
/**
 * @group Exemplar
 */
class ExemplarController extends Controller {
    /**
     * Fetch Exemplars by book_id
     * 
     * @unauthenticated
     *
     * @urlParam book_id integer required The book's ID. Example: 831
     * @bodyParam condition integer optional Worst condition acceptable (1=LikeNew, 2=Good, 3=Worn, 4=Damaged). Example: 2
     * @bodyParam borrowable boolean optional Set it to false if unborrowable exemplars are acceptable to be listed. Default is true. Example: true
     * 
     * @response 200 {"data":[{"id":337,"borrowable":1,"book_id":11,"book_name":"Qui saepe et nisi enim.","condition_value":1,"condition_name":"LikeNew"},{"id":487,"borrowable":1,"book_id":11,"book_name":"Qui saepe et nisi enim.","condition_value":2,"condition_name":"Good"}]}
     * @response 204 scenario="No exemplars found for the given book_id."
     * @response 404 scenario="Book not found." {"errors": [list]}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function index(int $book_id, Request $request):AnonymousResourceCollection|JsonResponse|Response {
        $request->merge([ 'book_id' => $book_id ]);
        $validator = Validator::make($request->all(), [
            'book_id' => [ 'required', 'integer', 'min:1', 'exists:books,id' ],
            'condition' => [ 'nullable', 'integer', 'min:1', 'max:4' ],
            'borrowable' => [ 'nullable', 'boolean' ]  /* ?borrowable=1   ?borrowable=0 */
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->has('book_id')) $response = Response::HTTP_NOT_FOUND;
            else $response = Response::HTTP_UNPROCESSABLE_ENTITY;
            Misc::monitor('get', $response);
            return response()->json([
                'errors' => $validator->errors()
            ], $response);
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
    /**
     * Show Exemplar
     * 
     * @unauthenticated
     * 
     * @response 200 {"data":{"id":5,"borrowable":1,"book_id":167,"book_name":"Quo sint qui corporis.","condition_value":2,"condition_name":"Good"}}
     * @response 404 scenario="Exemplar not found." {"errors": [list]}
     */
    public function show(Exemplar $exemplar):ExemplarResource {
        Misc::monitor('get',Response::HTTP_OK);
        return ExemplarResource::make($exemplar);
    }
    /**
     * Create Exemplar
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     *
     * @bodyParam condition integer required The actual condition (1=LikeNew, 2=Good, 3=Worn, 4=Damaged). Example: 1
     * @bodyParam borrowable boolean optional If false then this exemplar cannot leave the library. Default is true. Example: true
     * 
     * @response 201 {"data":{"id":6,"borrowable":1,"book_id":167,"book_name":"Quo sint qui corporis.","condition_value":2,"condition_name":"Good"}}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
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
    /**
     * Update Exemplar
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     * 
     * @bodyParam change_donor boolean optional Set this field to true to update the donor. Default is false. Example: false
     * 
     * @response 201 {"data":{"id":6,"borrowable":1,"book_id":167,"book_name":"Quo sint qui corporis.","condition_value":2,"condition_name":"Good"}}
     * @response 404 scenario="Exemplar not found." {"errors": [list]}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function update(Request $request, Exemplar $exemplar):ExemplarResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'book_id' => [ 'nullable', 'required_without_all:condition,borrowable,change_donor', 'integer', 'min:1', 'exists:books,id' ],
            'condition' => [ 'nullable', 'required_without_all:book_id,borrowable,change_donor', 'integer', 'min:1', 'max:4' ],
            'borrowable' => [ 'nullable', 'required_without_all:book_id,condition,change_donor', 'boolean' ],
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
    /**
     * Delete Exemplar
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     *
     * @response 204
     * @response 404 scenario="Exemplar not found." {"errors": [list]}
     */
    public function destroy(Exemplar $exemplar):Response {
        $exemplar->delete();
        Misc::monitor('delete',Response::HTTP_NO_CONTENT);
        return response()->noContent();
    }
}