<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\WhoAmIResource;
/**
 * @group Auth
 */
class WhoAmIController extends Controller {
    /**
     * Who Am I
     * 
     * @authenticated
     * 
     * @response 200 {"id":1567,"name":"Mister Y Z","email":"misteryz@sample.biz"}
     */
    public function __invoke(Request $request) {
        return auth()->check() ? WhoAmIResource::make(User::find(auth()->id())) : null;
    }
}