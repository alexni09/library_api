<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\PasswordUpdateController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\WhoAmIController;
use App\Http\Controllers\Api\Main\CategoryController;
use App\Http\Controllers\Api\Main\BookController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', RegisterController::class);

Route::post('auth/login', LoginController::class);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);

Route::get('books', [BookController::class, 'index']);
Route::get('books/{book}', [BookController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('whoami', WhoAmIController::class);  // Added by me. 
                                                    // Can't be outside middleware!!
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::put('password', PasswordUpdateController::class);
    Route::post('auth/logout', LogoutController::class);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    Route::post('books', [BookController::class, 'store']);
    Route::put('books/{book}', [BookController::class, 'update']);
});