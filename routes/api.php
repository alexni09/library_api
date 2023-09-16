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
use App\Http\Controllers\Api\Main\BookDonationController;
use App\Http\Controllers\Api\Main\BorrowController;
use App\Http\Controllers\Api\Main\PaymentController;
use App\Http\Controllers\Api\Main\MoneyController;
use App\Http\Controllers\Api\Main\CountController;
use App\Http\Controllers\Api\Main\ExemplarLossController;

Route::post('auth/register', RegisterController::class);

Route::post('auth/login', LoginController::class);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);

Route::get('books-by-category/{category_id}', [BookController::class, 'booksByCategory']);
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
    Route::post('books/donate', BookDonationController::class);
    Route::post('borrow/{exemplar}', [BorrowController::class, 'borrow']);
    Route::get('borrowed-list', [BorrowController::class, 'index']);
    Route::patch('giveback/{exemplar_id}', [BorrowController::class, 'giveback']);
    Route::get('all-payments-total', [PaymentController::class, 'allPaymentsTotal']);
    Route::get('balance-due-open', [PaymentController::class, 'balanceDueOpen']);
    Route::get('balance-due-unpaid', [PaymentController::class, 'balanceDueUnpaid']);
    Route::get('list-all-payments', [PaymentController::class, 'listAllPayments']);
    Route::get('list-balance-due-unpaid', [PaymentController::class, 'listBalanceDueUnpaid']);
    Route::get('list-balance-due-open', [PaymentController::class, 'listBalanceDueOpen']);
    Route::patch('pay/{payment}', [PaymentController::class, 'pay']);
    Route::delete('exemplar-loss/{exemplar_id}', ExemplarLossController::class);
});

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

Route::get('monitor', MonitorController::class);

Route::get('money', MoneyController::class);

Route::get('count', CountController::class);