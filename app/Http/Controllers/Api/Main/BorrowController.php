<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Exemplar;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class BorrowController extends Controller {
    public function borrow(Exemplar $exemplar):JsonResponse {
        if (!$exemplar->borrowable) {
            Misc::monitor('post',Response::HTTP_FORBIDDEN);
            return response()->json([
                'errors' => 'This exemplar cannot leave the library.'
            ], Response::HTTP_FORBIDDEN);
        }
        $user_id = Auth::id();
        $user = User::withCount('unreturned')->find($user_id);
        if ($user->unreturned_count >= $user->maximum_borrowable) {
            Misc::monitor('post',Response::HTTP_FORBIDDEN);
            return response()->json([
                'errors' => 'User has reached the maximum borrowable limit (' . strval($user->maximum_borrowable) . ').'
            ], Response::HTTP_FORBIDDEN);
        }
        $exemplar1 = Exemplar::withCount('unreturned')->find($exemplar->id);
        if ($exemplar1->unreturned_count >= 1) {
            Misc::monitor('post',Response::HTTP_FORBIDDEN);
            return response()->json([
                'errors' => 'This exemplar is currently borrowed.'
            ], Response::HTTP_FORBIDDEN);
        }
        $now = Carbon::now();
        $exemplar->borrowed()->attach($user_id, [ 'borrowed' => $now ]);
        return response()->json([
            'data' => [
                'user_id' => $user_id,
                'exemplar_id' => $exemplar->id,
                'borrowed' => $now,
                'returned' => null
            ]
        ], Response::HTTP_CREATED);
    }
}