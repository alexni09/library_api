<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Http\Response;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller {
    public function index():AnonymousResourceCollection {
        return CategoryResource::collection(Category::all());
    }
}