<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class BookController extends Controller {
    public function index():AnonymousResourceCollection {
        Misc::monitor('get',Response::HTTP_OK);
        return BookResource::collection(Book::all());
    }

    public function show(Book $book):BookResource {
        Misc::monitor('get',Response::HTTP_OK);
        return BookResource::make($book);
    }

    public function store(Request $request):BookResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'category_id' => ['required', 'integer', 'min:1', 'exists:categories,id']
        ]);
        if ($validator->fails()) {
            Misc::monitor('post',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $book = Book::create($validator->validated());
        Misc::monitor('post',Response::HTTP_CREATED);
        return BookResource::make($book);
    }

    public function update(UpdateBookRequest $request, Book $book):BookResource {
        $name = $request->validated()['name'] ?? null;
        $rating = $request->validated()['rating'] ?? null;
        $category_id = $request->validated()['category_id'] ?? null;
        if (isset($name)) $book->name = $name;
        if (isset($rating)) $book->rating = $rating;
        if (isset($category_id)) $book->category_id = $category_id;
        $book->save();
        Misc::monitor('put',Response::HTTP_OK);
        return BookResource::make($book);
    }

    public function destroy(Book $book):Response {
        $book->delete();
        Misc::monitor('delete',Response::HTTP_NO_CONTENT);
        return response()->noContent();
    }
}