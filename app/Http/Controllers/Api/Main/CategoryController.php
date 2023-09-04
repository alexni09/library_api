<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\Misc;

class CategoryController extends Controller {
    public function index():AnonymousResourceCollection {
        Misc::monitor('get',200);
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category):CategoryResource {
        Misc::monitor('get',200);
        return CategoryResource::make($category);
    }

    public function store(StoreCategoryRequest $request):CategoryResource {
        $category = Category::create($request->validated());
        Misc::monitor('post',201);
        return CategoryResource::make($category);
    }

    public function update(StoreCategoryRequest $request, Category $category):CategoryResource {
        $category->update($request->validated());
        Misc::monitor('put',200);
        return CategoryResource::make($category);
    }

    public function destroy(Category $category):Response {
        $category->delete();
        Misc::monitor('delete',204);
        return response()->noContent();
    }
}