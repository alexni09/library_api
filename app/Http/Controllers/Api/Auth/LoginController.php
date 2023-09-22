<?php

namespace App\Http\Controllers\Api\Auth;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
/**
 * @group Auth
 */ 
class LoginController extends Controller {
    /**
     * Login
     * 
     * @unauthenticated
     * 
     * @response 201 {"access_token":"13|laravel_sanctum_iB5fCWeUGTlqcOtpoNo7yzGSecNDJn9FK1kSm3EJ90942cz1"}
     * @response 422 scenario="Incorrect credentials or validation Errors." {"errors": [list]}
     */
    public function __invoke(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        $user = User::where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
 
        $device    = substr($request->userAgent() ?? '', 0, 255);
        $expiresAt = $request->remember ? null : now()->addMinutes(config('session.lifetime'));
 
        return response()->json([
            'access_token' => $user->createToken($device, expiresAt: $expiresAt)->plainTextToken,
        ], Response::HTTP_CREATED);
    }
}