<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Http\Response;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller {
    public function index():AnonymousResourceCollection {
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category):CategoryResource {
        return CategoryResource::make($category);
    }

    public function store(StoreCategoryRequest $request):CategoryResource {
        $category = Category::create([ 'name' => $request('name') ]);
        return CategoryResource::make($category);
    }
}