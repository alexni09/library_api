<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Frontend\MonitorController;
use App\Http\Controllers\Api\Frontend\MoneyController;
use App\Http\Controllers\Api\Frontend\CountController;

Route::middleware('referer')->group(function () {
    Route::get('monitor', MonitorController::class);
    Route::get('money', MoneyController::class);
});

Route::get('count', CountController::class);