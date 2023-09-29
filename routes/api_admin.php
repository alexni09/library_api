<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Main\CategoryController;
use App\Http\Controllers\Api\Main\BookController;
use App\Http\Controllers\Api\Main\ExemplarController;

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    Route::get('books', [BookController::class, 'index']);
    Route::post('books', [BookController::class, 'store']);
    Route::put('books/{book}', [BookController::class, 'update']);
    Route::delete('books/{book}', [BookController::class, 'destroy']);
    Route::post('exemplars', [ExemplarController::class, 'store']);
    Route::put('exemplars/{exemplar}', [ExemplarController::class, 'update']);
    Route::delete('exemplars/{exemplar}', [ExemplarController::class, 'destroy']);
});