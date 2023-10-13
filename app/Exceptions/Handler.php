<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Misc;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler {
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register() {
        $this->renderable(function (NotFoundHttpException $e) {
            if (request()->is('api/*')) {
                Misc::monitor(strtolower(request()->method()), Response::HTTP_NOT_FOUND);
                return response()->json([
                    'error' => 'Object not found.'
                ], Response::HTTP_NOT_FOUND);
            }
        });
    }
}