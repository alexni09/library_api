<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Payment;
use App\Http\Resources\PaymentResource;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
/**
 * @group Payment
 */
class PaymentController extends Controller {
    /**
     * User receives a sum of all payments
     * 
     * @authenticated
     * 
     * @response 200 {"data":{"all_payments_total":56789}}
     */
    public function allPaymentsTotal():JsonResponse {
        Misc::monitor('get',Response::HTTP_OK);
        return response()->json([
            'data' => [ 'all_payments_total' => Auth::User()->allPaymentsTotal() ]
        ], Response::HTTP_OK);
    }
    /**
     * User receives a sum of all open payments
     * 
     * @authenticated
     * 
     * @response 200 {"data":{"all_payments_total":12345}}
     */
    public function balanceDueOpen():JsonResponse {
        Misc::monitor('get',Response::HTTP_OK);
        return response()->json([
            'data' => [ 'balance_due_open' => Auth::User()->balanceDueOpen() ]
        ], Response::HTTP_OK);
    }
    /**
     * User receives a sum of all unpaid payments
     * 
     * @authenticated
     * 
     * @response 200 {"data":{"all_payments_total":7891}}
     */
    public function balanceDueUnpaid():JsonResponse {
        Misc::monitor('get',Response::HTTP_OK);
        return response()->json([
            'data' => [ 'balance_due_unpaid' => Auth::User()->balanceDueUnpaid() ]
        ], Response::HTTP_OK);
    }

    protected function list(EloquentCollection $payments):AnonymousResourceCollection|Response {
        if ($payments->isEmpty()) {
            Misc::monitor('post',Response::HTTP_NO_CONTENT);
            return response()->noContent();
        }
        Misc::monitor('get',Response::HTTP_OK);
        return PaymentResource::collection($payments);
    }
    /**
     * User lists all payments
     * 
     * @authenticated
     * 
     * @response 200 {"data":[{"id":1,"exemplar_id":7,"due_value":900,"due_from":"2023-09-23 00:07:00","due_at":"2023-09-23 00:08:00","paid_at":"2023-09-23 00:24:55"},{"id":2,"exemplar_id":6,"due_value":2170,"due_from":"2023-09-23 00:06:51","due_at":"2023-09-23 00:10:01","paid_at":null}]}
     * @response 204 scenario="No records found."
     */
    public function listAllPayments():AnonymousResourceCollection|Response {
        return $this->list(Payment::allPaymentsList(Auth::id()));
    }
    /**
     * User lists unpaid payments
     * 
     * @authenticated
     * 
     * @response 200 {"data":[{"id":1,"exemplar_id":7,"due_value":900,"due_from":"2023-09-23 00:07:00","due_at":"2023-09-23 00:08:00","paid_at":null},{"id":2,"exemplar_id":6,"due_value":2170,"due_from":"2023-09-23 00:06:51","due_at":"2023-09-23 00:10:01","paid_at":null}]}
     * @response 204 scenario="No records found."
     */
    public function listBalanceDueUnpaid():AnonymousResourceCollection|Response {
        return $this->list(Payment::balanceDueUnpaidList(Auth::id()));
    }
    /**
     * User lists open payments
     * 
     * @authenticated
     * 
     * @response 200 {"data":[{"id":2,"exemplar_id":6,"due_value":2170,"due_from":"2023-09-23 00:06:51","due_at":"2023-09-23 00:10:01","paid_at":null},{"id":1,"exemplar_id":7,"due_value":900,"due_from":"2023-09-23 00:07:00","due_at":"2023-09-23 00:08:00","paid_at":null}]}
     * @response 204 scenario="No records found."
     */
    public function listBalanceDueOpen():AnonymousResourceCollection|Response {
        return $this->list(Payment::balanceDueOpenList(Auth::id()));
    }
    /**
     * User pays for borrowing an exemplar
     * 
     * @authenticated
     * 
     * @response 200 {"data":{"id":1,"exemplar_id":7,"due_value":900,"received":3000,"change":2100,"due_from":"2023-09-23 00:07:00","due_at":"2023-09-23 00:08:00","paid_at":"2023-09-23 00:24:55","message":"Payment received. Thank you!"}}
     * @response 403 scenario="Can't pay for someone elses'." {"errors": "It is not allowed to pay for someone else's invoices."}
     * @response 404 scenario="Payment not found." {"errors": [list]}
     * @response 422 scenario="Already paid." {"errors": "Already paid."}
     * @response 422 scenario="Validation Errors. (Includes underpayment attempt.)" {"errors": [list]}
     */
    public function pay(Request $request, Payment $payment):JsonResponse {
        $validator = Validator::make($request->all(), [
            'money' => [ 'required', 'integer', 'min:'.strval($payment->due_value) ]
        ]);
        if ($validator->fails()) {
            Misc::monitor('patch',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($payment->user_id !== Auth::id()) {
            Misc::monitor('patch',Response::HTTP_FORBIDDEN);
            return response()->json([
                'errors' => 'It is not allowed to pay for someone else\'s invoices.'
            ], Response::HTTP_FORBIDDEN);
        }
        if ($payment->paid_at !== null) {
            Misc::monitor('patch',Response::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json([
                'errors' => 'Already paid.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $money = intval($validator->validated()['money']);
        $change = $money - $payment->due_value;
        switch(rand(1,5)) {
            case 1: $message = 'Thank you!'; break;
            case 2: $message = 'Payment received with thanks!'; break; 
            case 3: $message = 'Thank you for your business.'; break;
            case 4: $message = 'Payment received. Thank you!'; break;
            case 5: $message = 'Thanks.'; break;
        }
        $payment->update([ 'paid_at' => (Carbon::now())->toDateTimeString() ]);
        Misc::monitor('patch',Response::HTTP_OK);
        Misc::makeMoney($payment->due_value);
        return response()->json([
            'data' => [
                'id' => $payment->id,
                'exemplar_id' => $payment->exemplar_id,
                'due_value' => $payment->due_value,
                'received' => $money,
                'change' => $change,
                'due_from' => $payment->due_from,
                'due_at' => $payment->due_at,
                'paid_at' => $payment->paid_at,
                'message' => $message
            ] 
        ], Response::HTTP_OK);
    }
}