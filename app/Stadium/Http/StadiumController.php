<?php

namespace App\Stadium\Http;

use App\System\Http\Controllers\Controller;
use App\Stadium\Http\Requests\PostStadiumsRequest;
use Illuminate\Http\JsonResponse;

class StadiumController extends Controller
{
    /**
     * Process uploaded CSV and enrich response with weather data.
     * 
     * User is expected to have uploaded a file, keyed as `stadiums`
     */
    public function post(PostStadiumsRequest $request): JsonResponse
    {
        // todo: handle the input from the request, return real response
        return response()->json();
    }
}
