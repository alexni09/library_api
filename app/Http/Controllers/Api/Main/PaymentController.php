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

class PaymentController extends Controller {
    public function allPaymentsTotal():JsonResponse {
        Misc::monitor('get',Response::HTTP_OK);
        return response()->json([
            'data' => [ 'all_payments_total' => Auth::User()->allPaymentsTotal() ]
        ], Response::HTTP_OK);
    }

    public function balanceDueOpen():JsonResponse {
        Misc::monitor('get',Response::HTTP_OK);
        return response()->json([
            'data' => [ 'balance_due_open' => Auth::User()->balanceDueOpen() ]
        ], Response::HTTP_OK);
    }

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

    public function listAllPayments():AnonymousResourceCollection|Response {
        return $this->list(Payment::allPaymentsList(Auth::id()));
    }

    public function listBalanceDueUnpaid():AnonymousResourceCollection|Response {
        return $this->list(Payment::balanceDueUnpaidList(Auth::id()));
    }

    public function listBalanceDueOpen():AnonymousResourceCollection|Response {
        return $this->list(Payment::balanceDueOpenList(Auth::id()));
    }

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