<?php

namespace App\Http\Controllers\Api\Auth;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
/**
 * @group Auth
 */
class PasswordUpdateController extends Controller {
    /**
     * Password Update
     * 
     * @authenticated
     * 
     * @bodyParam password_confirmation string required Must have the same value as password. No-example
     * 
     * @response 202 {"message":"Your password has been updated."}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function __invoke(Request $request) {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);
 
        auth()->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);
 
        return response()->json([
            'message' => 'Your password has been updated.',
        ], Response::HTTP_ACCEPTED);
    }
}