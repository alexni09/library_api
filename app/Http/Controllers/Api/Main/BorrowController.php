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
use App\Http\Resources\ExemplarResource;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

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
        $due = (Carbon::now())->addMinutes($exemplar->rental_maximum_minutes);
        $exemplar->borrowed()->attach($user_id, [ 'borrowed' => $now ]);
        Misc::monitor('post',Response::HTTP_CREATED);
        return response()->json([
            'data' => [
                'user_id' => $user_id,
                'exemplar_id' => $exemplar->id,
                'borrowed' => $now,
                'returned' => null,
                'return_due' => $due,
                'maximum_minutes' => $exemplar->rental_maximum_minutes
            ]
        ], Response::HTTP_CREATED);
    }

    public function index():AnonymousResourceCollection|Response {
        $user_id = Auth::id();
        $user = User::withCount('unreturned')->find($user_id);
        if ($user->unreturned_count === 0) {
            Misc::monitor('post',Response::HTTP_NO_CONTENT);
            return response()->noContent();
        }
        $user2 = User::with('unreturned')->find($user_id);
        Misc::monitor('get',Response::HTTP_OK);
        return ExemplarResource::collection($user2->unreturned()->get());
    }

    public function giveback(int $exemplar_id, Request $request):JsonResponse {
        $request->merge([ 'exemplar_id' => $exemplar_id ]);
        $validator = Validator::make($request->all(), [
            'exemplar_id' => [ 'required', 'integer', 'min:1', 'exists:exemplars,id' ],
            'condition' => [ 'nullable', 'integer', 'min:1', 'max:4' ]
        ]);
        if ($validator->fails()) {
            Misc::monitor('patch',Response::HTTP_NOT_FOUND);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_NOT_FOUND);
        }
        $exemplar = Exemplar::with('unreturned')->find($exemplar_id);
        $exemplar_user = DB::table('exemplar_user')->where('exemplar_id', $exemplar_id)->where('user_id', Auth::id())->whereNull('returned')->first();
        if ($exemplar_user === null || $exemplar_user->user_id != Auth::id() || $exemplar_user->exemplar_id != $exemplar_id) {
            Misc::monitor('patch',Response::HTTP_NOT_FOUND);
            return response()->json([
                'errors' => 'Exemplar #' . strval($exemplar_id) . ' is not borrowed with this user #' . strval(Auth::id()) . '.'
            ], Response::HTTP_NOT_FOUND);
        }
        $condition = isset($request['condition']) ? intval($request['condition']) : $exemplar->condition->value;
        if ($condition < $exemplar->condition->value) {
            Misc::monitor('patch',Response::HTTP_FORBIDDEN);
            return response()->json([
                'errors' => 'Returning a book in a better shape is impossible!'
            ], Response::HTTP_FORBIDDEN);
        }
        $now = Carbon::now();
        $due = (new Carbon($exemplar->borrowedTimestamp()))->addMinutes($exemplar->rental_maximum_minutes);
        $computedDelayFine = $exemplar->computedDelayFine();
        $computedDamageFine = $exemplar->computedDamageFine($condition);
        $total = $exemplar->fee + $computedDelayFine + $computedDamageFine;
        $payment_due = (new Carbon($due))->addMinutes($exemplar->payment_maximum_minutes);
        DB::beginTransaction();
        $exemplar->unreturned()->updateExistingPivot(Auth::id(), [ 'returned' => $now ]);
        $exemplar->update([ 'condition' => $condition ]);
        Payment::create([
            'exemplar_id' => $exemplar->id,
            'user_id' => Auth::id(),
            'due_value' => $total,
            'due_from' => $due,
            'due_at' => $payment_due
        ]);
        DB::commit();
        Misc::monitor('patch',Response::HTTP_OK);
        return response()->json([
            'data' => [
                'user_id' => Auth::id(),
                'exemplar_id' => $exemplar->id,
                'condition' => $exemplar->condition,
                'returned' => $now,
                'due' => $due,
                'fee_per_rental' => $exemplar->fee,
                'fine_per_delay' => $computedDelayFine,
                'fine_per_damage' => $computedDamageFine,
                'total_payment_due' => $total,
                'payment_due' => $payment_due
            ]
        ], Response::HTTP_OK);
    }
}