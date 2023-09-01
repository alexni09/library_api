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

class BookController extends Controller {
    public function index():AnonymousResourceCollection {
        return BookResource::collection(Book::all());
    }

    public function show(Book $book):BookResource {
        return BookResource::make($book);
    }

    public function store(StoreBookRequest $request):BookResource {
        $book = Book::create($request->validated());
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
        return BookResource::make($book);
    }

}