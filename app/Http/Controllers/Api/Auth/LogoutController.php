<?php

namespace App\Http\Controllers\Api\Auth;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
 * @group Auth
 */
class LogoutController extends Controller {
    public function __invoke(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
 
        return response()->noContent();
    }
}