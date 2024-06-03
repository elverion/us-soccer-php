<?php

namespace App\Stadium\Http;

use App\System\Http\Controllers\Controller;
use App\Stadium\Http\Requests\PostStadiumsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use App\Stadium\Http\Requests\{IndexStadiumsRequest, StoreStadiumsRequest};
use App\Stadium\Data\StadiumCsvProcessor;

class StadiumController extends Controller
{
    public function __construct(
        protected readonly StadiumService $stadiumService,
    ) {
    }
    /**
     * Process uploaded CSV and enrich response with weather data.
     * 
     * User is expected to have uploaded a file, keyed as `stadiums`
     */
    public function store(StoreStadiumsRequest $request): JsonResponse
    {
        // todo: handle the input from the request, return real response
        /** @var UploadedFile $file */
        $file = $request->stadiums;

        StadiumCsvProcessor::process($file->getContent(), function (StadiumData $data) {
            $this->stadiumService->updateOrCreate($data);
        });

        return response()->json(Response::HTTP_CREATED);
    }
}
