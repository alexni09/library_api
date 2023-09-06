<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ExemplarResource;
use App\Models\Exemplar;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\Misc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ExemplarController extends Controller {
    public function index(int $book_id):AnonymousResourceCollection {
        Misc::monitor('get',Response::HTTP_OK);
        return ExemplarResource::collection(Exemplar::with('book')->where('book_id',$book_id)->get());
    }

    public function show(Exemplar $exemplar):ExemplarResource {
        Misc::monitor('get',Response::HTTP_OK);
        return ExemplarResource::make($exemplar);
    }

}