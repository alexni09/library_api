<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

Route::get('/db', function() {
    $r = DB::table('information_schema.tables')->where('TABLE_NAME','exemplars')->first()->TABLE_ROWS;
    dd($r);
});

Route::get('/', function () {
    return Inertia::render('Index');
});