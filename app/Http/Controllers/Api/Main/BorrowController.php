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
/**
 * @group Borrow
 */
class BorrowController extends Controller {
    /**
     * User borrows an exemplar
     * 
     * @authenticated
     * 
     * @response 201 {"data":{"user_id":4,"exemplar_id":9,"borrowed":"2023-09-22T23:23:04.552027Z","returned":null,"return_due":"2023-09-22T23:24:04.552034Z","maximum_minutes":1}}
     * @response 402 scenario="Payment required." {"errors": "This borrowing is suspended because of open payments."}
     * @response 403 scenario="Exemplar cannot leave the library." {"errors": "This exemplar cannot leave the library."}
     * @response 403 scenario="Maximum borrowings reached for the user." {"errors": "User has reached the maximum borrowable limit (3)."}
     * @response 403 scenario="Exemplar is currently borrowed." {"errors": "This exemplar is currently borrowed."}
     * @response 404 scenario="Exemplar not found." {"errors": [list]}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
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
        if (Payment::hasOpenPayments($user_id)) {
            Misc::monitor('post', 402);
            return response()->json([
                'errors' => 'This borrowing is suspended because of open payments.'
            ], 402);
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
    /**
     * User lists his/hers unreturned exemplars
     * 
     * @authenticated
     * 
     * @response 200 {"data":[{"id":5,"borrowable":1,"book_id":61,"book_name":"Id fugit aut rem suscipit.","condition_value":2,"condition_name":"Good"},{"id":7,"borrowable":1,"book_id":93,"book_name":"Libero saepe aut facilis.","condition_value":1,"condition_name":"LikeNew"},{"id":8,"borrowable":1,"book_id":165,"book_name":"Ut ratione eos sed sunt.","condition_value":1,"condition_name":"LikeNew"}]}
     * @response 204 scenario="User has no unreturned exemplars."
     */
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
    /**
     * User gives back an exemplar
     * 
     * @authenticated
     *
     * @bodyParam condition integer optional The actual exemplar condition (1=LikeNew, 2=Good, 3=Worn, 4=Damaged). Example: 4
     * 
     * @response 200 {"data":{"user_id":4,"exemplar_id":7,"condition":1,"returned":"2023-09-23T00:06:19.191688Z","due":"2023-09-23T00:07:00.000000Z","fee_per_rental":900,"fine_per_delay":0,"fine_per_damage":0,"total_payment_due":900,"payment_due":"2023-09-23T00:08:00.000000Z"}}
     * @response 404 scenario="Exemplar not found." {"errors": [list]}
     * @response 422 scenario="Validation Errors." {"errors": [list]}
     */
    public function giveback(int $exemplar_id, Request $request):JsonResponse {
        $request->merge([ 'exemplar_id' => $exemplar_id ]);
        $validator = Validator::make($request->all(), [
            'exemplar_id' => [ 'required', 'integer', 'min:1', 'exists:exemplars,id' ],
            'condition' => [ 'nullable', 'integer', 'min:1', 'max:4' ]
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->has('exemplar_id')) $response = Response::HTTP_NOT_FOUND;
            else $response = Response::HTTP_UNPROCESSABLE_ENTITY;
            Misc::monitor('patch', $response);
            return response()->json([
                'errors' => $validator->errors()
            ], $response);
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
        /* returned after the due minute: */
        $payment_due1 = (new Carbon($now))->addMinutes($exemplar->payment_maximum_minutes);
        /* returned before the due minute: */
        $payment_due2 = (new Carbon($exemplar->borrowedTimestamp()))->addMinutes($exemplar->rental_maximum_minutes)->addMinutes($exemplar->payment_maximum_minutes);
        if ($payment_due1->gt($payment_due2)) $payment_due = $payment_due1; else $payment_due = $payment_due2;
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