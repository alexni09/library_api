<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Exemplar;
use App\Services\Misc;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;
use Carbon\Carbon;
/**
 * @group ExemplarLoss
 */
class ExemplarLossController extends Controller {
    /**
     * User loses an exemplar
     * 
     * @authenticated
     * 
     * @urlParam exemplar_id required integer The ID of the lost exemplar. Example: 54
     *
     * @response 200 {"data":{"user_id":4,"old_exemplar_id":27,"lost_or_destroyed":"2023-09-22T21:36:04.817027Z","due":"2023-09-22T21:35:50.000000Z","fine_per_loss_or_destruction":230000,"payment_due":"2023-09-22T21:37:04.817027Z"}}
     * @response 404 scenario="Exemplar not found or not borrowed by the user." {"errors": [list]}
     */
    public function __invoke(int $exemplar_id, Request $request):JsonResponse {
        $request->merge([ 'exemplar_id' => $exemplar_id ]);
        $validator = Validator::make($request->all(), [
            'exemplar_id' => [ 'required', 'integer', 'min:1', 'exists:exemplars,id' ]
        ]);
        if ($validator->fails()) {
            Misc::monitor('delete',Response::HTTP_NOT_FOUND);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_NOT_FOUND);
        }
        $exemplar = Exemplar::with('unreturned')->find($exemplar_id);
        $exemplar_user = DB::table('exemplar_user')->where('exemplar_id', $exemplar_id)->where('user_id', Auth::id())->whereNull('returned')->first();
        if ($exemplar_user === null || $exemplar_user->user_id != Auth::id() || $exemplar_user->exemplar_id != $exemplar_id) {
            Misc::monitor('delete',Response::HTTP_NOT_FOUND);
            return response()->json([
                'errors' => 'Exemplar #' . strval($exemplar_id) . ' is not borrowed with this user #' . strval(Auth::id()) . '.'
            ], Response::HTTP_NOT_FOUND);
        }
        $now = Carbon::now();
        $due = (new Carbon($exemplar->borrowedTimestamp()))->addMinutes($exemplar->rental_maximum_minutes);
        $total = $exemplar->fine_per_loss;
        /* returned after the due minute: */
        $payment_due1 = (new Carbon($now))->addMinutes($exemplar->payment_maximum_minutes);
        /* returned before the due minute: */
        $payment_due2 = (new Carbon($exemplar->borrowedTimestamp()))->addMinutes($exemplar->rental_maximum_minutes)->addMinutes($exemplar->payment_maximum_minutes);
        if ($payment_due1->gt($payment_due2)) $payment_due = $payment_due1; else $payment_due = $payment_due2;
        DB::beginTransaction();
        $exemplar->unreturned()->updateExistingPivot(Auth::id(), [ 'returned' => $now ]);
        $exemplar->delete();
        Payment::create([
            'exemplar_id' => null,
            'user_id' => Auth::id(),
            'due_value' => $total,
            'due_from' => $due,
            'due_at' => $payment_due
        ]);
        DB::commit();
        Misc::monitor('delete',Response::HTTP_OK);
        return response()->json([
            'data' => [
                'user_id' => Auth::id(),
                'old_exemplar_id' => $exemplar_id,
                'lost_or_destroyed' => $now,
                'due' => $due,
                'fine_per_loss_or_destruction' => $total,
                'payment_due' => $payment_due
            ]
        ], Response::HTTP_OK);
    }
}