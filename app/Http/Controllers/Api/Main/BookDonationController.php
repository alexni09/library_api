<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ExemplarResource;
use App\Models\Exemplar;
use App\Models\Book;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
/**
 * @group BookDonation
 */
class BookDonationController extends Controller {
    /**
     * User donates a book
     * 
     * @authenticated
     *
     * @bodyParam condition integer required The actual exemplar condition (1=LikeNew, 2=Good, 3=Worn, 4=Damaged). Example: 4
     * 
     * @response 201 {"data":{"id":123,"borrowable":1,"book_id":98,"book_name":"Ut in nam ea recusandae.","condition_value":4,"condition_name":"Damaged"}}
     * @response 404 scenario="Category not found." {"errors": [list]}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function __invoke(Request $request):ExemplarResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'category_id' => [ 'required', 'integer', 'min:1', 'exists:categories,id' ],
            'name' => [ 'required', 'string' ],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'condition' => [ 'required', 'integer', 'min:1', 'max:4' ]        
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->has('category_id')) $response = Response::HTTP_NOT_FOUND;
            else $response = Response::HTTP_UNPROCESSABLE_ENTITY;
            Misc::monitor('post', $response);
            return response()->json([
                'errors' => $validator->errors()
            ], $response);
        }
        DB::beginTransaction();
        $book = Book::create([
            'category_id' => $request['category_id'],
            'name' => $request['name'],
            'rating' => $request['rating']
        ]);
        $exemplar = Exemplar::create([
            'book_id' => $book->id,
            'condition' => $request['condition'],
            'borrowable' => true,
            'user_id' => Auth::id()
        ]);
        DB::commit();
        Misc::monitor('post', Response::HTTP_CREATED);
        return ExemplarResource::make($exemplar);
    }
}