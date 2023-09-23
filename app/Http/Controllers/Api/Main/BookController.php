<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Models\Category;
/**
 * @group Book
 */
class BookController extends Controller {
    /**
     * List All Books
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     *
     * @response 200 {"data":[{"id":51,"name":"Totam et et libero quis.","rating_value":2,"rating_name":"Bad","category_id":6,"category_name":"Optio at eius qui ipsa."},{"id":52,"name":"Et saepe ut sint aut magnam.","rating_value":3,"rating_name":"Reasonable","category_id":6,"category_name":"Optio at eius qui ipsa."}]}
     */
    public function index():AnonymousResourceCollection {
        Misc::monitor('get',Response::HTTP_OK);
        return BookResource::collection(Book::all());
    }
    /**
     * Show Book
     * 
     * @unauthenticated
     * 
     * @response 200 {"data":{"id":51,"name":"Totam et et libero quis.","rating_value":2,"rating_name":"Bad","category_id":6,"category_name":"Optio at eius qui ipsa."}}
     * @response 404 scenario="Book not found." {"errors": [list]}
     */
    public function show(Book $book):BookResource {
        Misc::monitor('get',Response::HTTP_OK);
        return BookResource::make($book);
    }
    /**
     * Store Book
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     *
     * @response 201 {"data":{"id":51,"name":"Totam et et libero quis.","rating_value":2,"rating_name":"Bad","category_id":6,"category_name":"Optio at eius qui ipsa."}}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
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
    /**
     * Update Book
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     *
     * @response 200 {"data":{"id":51,"name":"Totam et et libero quis.","rating_value":2,"rating_name":"Bad","category_id":6,"category_name":"Optio at eius qui ipsa."}}
     * @response 404 scenario="Book not found." {"errors": [list]}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function update(Request $request, Book $book):BookResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'required_without_all:rating,category_id', 'string', 'max:255'],
            'rating' => ['nullable', 'required_without_all:name,category_id', 'integer', 'min:1', 'max:5'],
            'category_id' => ['nullable', 'required_without_all:name,rating', 'integer', 'min:1', 'exists:categories,id']
        ]);
        if ($validator->fails()) {
            Misc::monitor('put',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $name = $validator->validated()['name'] ?? null;
        $rating = $validator->validated()['rating'] ?? null;
        $category_id = $validator->validated()['category_id'] ?? null;
        if (isset($name)) $book->name = $name;
        if (isset($rating)) $book->rating = $rating;
        if (isset($category_id)) $book->category_id = $category_id;
        $book->save();
        Misc::monitor('put',Response::HTTP_OK);
        return BookResource::make($book);
    }
    /**
     * Delete Book
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     *
     * @response 204
     * @response 404 scenario="Book not found." {"errors": [list]}
     */
    public function destroy(Book $book):Response {
        $book->delete();
        Misc::monitor('delete',Response::HTTP_NO_CONTENT);
        return response()->noContent();
    }
    /**
     * Fetch Books By category_id
     * 
     * @unauthenticated
     * 
     * @urlParam category_id integer required The category's ID. Example: 82
     * 
     * @response 200 {"data":[{"id":51,"name":"Totam et et libero quis.","rating_value":2,"rating_name":"Bad","category_id":6,"category_name":"Optio at eius qui ipsa."},{"id":52,"name":"Et saepe ut sint aut magnam.","rating_value":3,"rating_name":"Reasonable","category_id":6,"category_name":"Optio at eius qui ipsa."}]}
     * @response 204 scenario="No books found for the given category_id."
     * @response 404 scenario="Category not found." {"errors": [list]}
     */
    public function booksByCategory(int $category_id, Request $request):JsonResponse|Response|AnonymousResourceCollection {
        $request->merge([ 'category_id' => $category_id ]);
        $validator = Validator::make($request->all(), [
            'category_id' => ['required', 'integer', 'min:1', 'exists:categories,id']
        ]);
        if ($validator->fails()) {
            Misc::monitor('get',Response::HTTP_NOT_FOUND);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_NOT_FOUND);
        }
        if (Category::withCount('books')->find($category_id)->books_count === 0) {
            Misc::monitor('get',Response::HTTP_NO_CONTENT);
            return response()->noContent();
        }
        Misc::monitor('get',Response::HTTP_OK);
        return BookResource::collection(Book::where('category_id',$category_id)->get());
    }
}