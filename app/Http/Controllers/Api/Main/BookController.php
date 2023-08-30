<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\StoreBookRequest;

class BookController extends Controller {
    public function index():AnonymousResourceCollection {
        return BookResource::collection(Book::all());
    }

    public function show(Book $book):BookResource {
        return BookResource::make($book);
    }
}