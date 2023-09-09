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
use App\Http\Resources\BorrowResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BorrowController extends Controller {
    public function borrow(Exemplar $exemplar):BorrowResource|JsonResponse {
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
        return response()->json([
            'errors' => $user->maximum_borrowable
        ], Response::HTTP_CREATED);
    }
}