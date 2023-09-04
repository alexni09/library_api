<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redis;


Route::get('/redis', function () {
    //Redis::set('key2','pigeon');   // a test
    //dd(Redis::get('key2'));
    Redis::lpush('mylist','111111');
    Redis::lpush('mylist','22222');
    Redis::lpush('mylist','333');
    Redis::lpush('mylist','44444444');
    Redis::lpush('mylist','5555');
    //$leng = Redis::llen('mylist');
    //dd($leng);   // a test
    $arr = Redis::lrange('mylist',0,-1);
    dd($arr);   // a test
});


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
