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
use App\Http\Controllers\Api\Main\ExemplarController;
use App\Http\Controllers\Api\Main\MonitorController;
use App\Http\Controllers\Api\Main\ExemplarDonationController;

Route::post('auth/register', RegisterController::class);

Route::post('auth/login', LoginController::class);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);

Route::get('books', [BookController::class, 'index']);
Route::get('books/{book}', [BookController::class, 'show']);

Route::get('exemplars/{exemplar}', [ExemplarController::class, 'show']);
Route::get('exemplars/list/{book_id}', [ExemplarController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('whoami', WhoAmIController::class);
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::put('password', PasswordUpdateController::class);
    Route::post('auth/logout', LogoutController::class);
    Route::post('exemplars/donate', ExemplarDonationController::class);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    Route::post('books', [BookController::class, 'store']);
    Route::put('books/{book}', [BookController::class, 'update']);
    Route::delete('books/{book}', [BookController::class, 'destroy']);
    Route::post('exemplars', [ExemplarController::class, 'store']);
});

Route::get('monitor', MonitorController::class);