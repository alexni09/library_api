<?php

namespace App\Http\Controllers\Api\Auth;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
/**
 * @group Auth
 */
class RegisterController extends Controller {
    /**
     * User Registration
     * 
     * @unauthenticated
     * 
     * @bodyParam password_confirmation string required Must have the same value as password. No-example
     * 
     * @response 201 {"access_token":"1|laravel_sanctum_iB5fCwdUGTlqcOtpoNo7yzGSecNDJn9FK1kSm3EJ90942cc1"}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function __invoke(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
 
        event(new Registered($user));
 
        $device = substr($request->userAgent() ?? '', 0, 255);
 
        return response()->json([
            'access_token' => $user->createToken($device)->plainTextToken,
        ], Response::HTTP_CREATED);
    }
}