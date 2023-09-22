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
/**
 * @group Category
 */
class CategoryController extends Controller {
    /**
     * List Categories
     * 
     * @unauthenticated
     * 
     * @response 200 { "data": [ { "id": 4, "name": "Odit illum magnam ut et et." }, { "id": 8, "name": "Iure aut ab tempore sed." } ] }
     */
    public function index(Request $request):AnonymousResourceCollection|JsonResponse {
        $category_max = Category::latest('id')->first()->id;
        $validator = Validator::make($request->all(), [
            'start' => [ 'nullable', 'integer', 'min:1', 'max:' . $category_max ]
        ]);
        if ($validator->fails()) {
            Misc::monitor('post',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $start = $request['start'] ?? 1;
        Misc::monitor('get',Response::HTTP_OK);
        return CategoryResource::collection(Category::where('id', '>=', $start)->limit(env('CATEGORY_CHUNK',200))->get());
    }
    /**
     * Show Category
     * 
     * @unauthenticated
     * 
     * @apiResource App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category
     */
    public function show(Category $category):CategoryResource {
        Misc::monitor('get',Response::HTTP_OK);
        return CategoryResource::make($category);
    }
    /**
     * Create Category
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     *
     * @response 201 {"data":{"id":8,"name":"Iure aut ab tempore sed."}}
     */
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
    /**
     * Update Category
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category
     */
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
    /**
     * Delete Category
     * 
     * <small class="badge badge-purple">admin</small>
     * 
     * @authenticated
     * 
     * @response 204
     */
    public function destroy(Category $category):Response {
        $category->delete();
        Misc::monitor('delete',Response::HTTP_NO_CONTENT);
        return response()->noContent();
    }
}