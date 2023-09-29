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
use App\Http\Controllers\Api\Main\ExemplarDonationController;
use App\Http\Controllers\Api\Main\BookDonationController;
use App\Http\Controllers\Api\Main\BorrowController;
use App\Http\Controllers\Api\Main\PaymentController;
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