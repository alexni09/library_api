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
use Illuminate\Support\Facades\Auth;
/**
 * @group ExemplarDonation
 */
class ExemplarDonationController extends Controller {
    /**
     * User donates an exemplar
     * 
     * @authenticated
     *
     * @bodyParam condition integer required The actual condition (1=LikeNew, 2=Good, 3=Worn, 4=Damaged). Example: 1
     * 
     * @response 200 {"data":{"id":123,"borrowable":1,"book_id":98,"book_name":"Ut in nam ea recusandae.","condition_value":1,"condition_name":"LikeNew"}}
     * @response 404 scenario="Book not found." {"errors": [list]}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function __invoke(Request $request):ExemplarResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'book_id' => [ 'required', 'integer', 'min:1', 'exists:books,id' ],
            'condition' => [ 'required', 'integer', 'min:1', 'max:4' ]        
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->has('book_id')) $response = Response::HTTP_NOT_FOUND;
            else $response = Response::HTTP_UNPROCESSABLE_ENTITY;
            Misc::monitor('post',$response);
            return response()->json([
                'errors' => $validator->errors()
            ], $response);
        }
        $exemplar = Exemplar::create([
            'book_id' => $request['book_id'],
            'condition' => $request['condition'],
            'borrowable' => true,
            'user_id' => Auth::id()
        ]);
        Misc::monitor('post',Response::HTTP_CREATED);
        return ExemplarResource::make($exemplar);
    }
}