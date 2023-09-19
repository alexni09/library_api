<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

Route::get('/db', function() {
    $r = DB::table('information_schema.tables')->selectRaw('sum(data_length) + sum(index_length) as total_sum')->first()->total_sum;
    dd($r);
});

Route::get('/', function () {
    return Inertia::render('Index');
});