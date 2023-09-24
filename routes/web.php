<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index', [
        'ip_list' => [
            '127.0.0.1' => 'http://localhost:8000/',
            env('CLIENT_IP_1') => env('CLIENT_URL_1'),
            env('CLIENT_IP_2') => env('CLIENT_URL_2')
        ]
    ]);
});