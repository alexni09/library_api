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
use App\Models\User;
use Carbon\Carbon;
use App\Models\Payment;

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
}