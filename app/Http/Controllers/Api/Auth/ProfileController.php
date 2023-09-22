<?php

namespace App\Http\Controllers\Api\Auth;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
/**
 * @group Auth
 */
class ProfileController extends Controller {
    /**
     * Show Profile
     * 
     * @authenticated
     * 
     * @response 200 {"name":"Mister X Y Z","email":"misterxyz@sample.website"}
     */
    public function show(Request $request) {
        return response()->json($request->user()->only('name', 'email'));
    }
    /**
     * Update Profile
     * 
     * @authenticated
     * 
     * @response 202 {"name":"Mister A B C","email":"misterabc@sample.site"}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function update(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->user())],
        ]);
 
        auth()->user()->update($validatedData);
 
        return response()->json($validatedData, Response::HTTP_ACCEPTED);
    }
}