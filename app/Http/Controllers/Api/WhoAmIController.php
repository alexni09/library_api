<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\WhoAmIResource;

class WhoAmIController extends Controller {
    public function __invoke(Request $request) {
        return auth()->check() ? WhoAmIResource::make(User::find(auth()->id())) : null;
    }
}