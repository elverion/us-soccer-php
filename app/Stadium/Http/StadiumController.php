<?php

namespace App\Stadium\Http;

use App\Http\Controllers\Controller;
use App\Location\Location;
use App\Stadium\Http\Requests\PostStadiumsRequest;
use App\Stadium\Stadium;
use App\Weather\Weather;
use Illuminate\Http\JsonResponse;

class StadiumController extends Controller
{
    public function post(PostStadiumsRequest $request): JsonResponse
    {
        // todo: handle the input from the request, attach weather data, return real response
        $stadiums = collect(
            new Stadium('todo', new Location('todo', 'todo', 1.0, 1.0), new Weather(20.0, 'todo')),
        );
        return StadiumResource::collection($stadiums)->response();
    }
}
