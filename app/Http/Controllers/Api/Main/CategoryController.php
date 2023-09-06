<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller {
    public function index():AnonymousResourceCollection {
        Misc::monitor('get',Response::HTTP_OK);
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category):CategoryResource {
        Misc::monitor('get',Response::HTTP_OK);
        return CategoryResource::make($category);
    }

    public function store(Request $request):CategoryResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => [ 'required', 'max:255' ]
        ]);
        if ($validator->fails()) {
            Misc::monitor('post',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $category = Category::create($validator->validated());
        Misc::monitor('post',Response::HTTP_CREATED);
        return CategoryResource::make($category);
    }

    public function update(Request $request, Category $category):CategoryResource|JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => [ 'required', 'max:255' ]
        ]);
        if ($validator->fails()) {
            Misc::monitor('put',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $category->update($validator->validated());
        Misc::monitor('put',Response::HTTP_OK);
        return CategoryResource::make($category);
    }

    public function destroy(Category $category):Response {
        $category->delete();
        Misc::monitor('delete',Response::HTTP_NO_CONTENT);
        return response()->noContent();
    }
}