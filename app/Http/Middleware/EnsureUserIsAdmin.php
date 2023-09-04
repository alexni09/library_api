<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Misc;

class EnsureUserIsAdmin {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (auth()->user()?->is_admin) return $next($request);
        else {
            Misc::monitor($request->method(),422);
            return response()->json([
                'errors' => ['general' => ['This operation is restricted to admins.']]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}