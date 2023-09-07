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

class BookDonationController extends Controller {
    public function __invoke(Request $request):ExemplarResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'category_id' => [ 'required', 'integer', 'min:1', 'exists:categories,id' ],
            'name' => [ 'required', 'string' ],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'condition' => [ 'required', 'integer', 'min:1', 'max:4' ]        
        ]);
        if ($validator->fails()) {
            Misc::monitor('post',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
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
        Misc::monitor('post',Response::HTTP_CREATED);
        return ExemplarResource::make($exemplar);
    }
}