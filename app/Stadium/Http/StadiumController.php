<?php

namespace App\Stadium\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

use App\System\Http\Controllers\Controller;
use App\Stadium\Http\Requests\{IndexStadiumsRequest, StoreStadiumsRequest};
use App\Stadium\Data\StadiumCsvProcessor;
use App\Stadium\Stadium;
use App\Stadium\StadiumData;
use App\Stadium\StadiumService;
use App\Weather\Api\WeatherApiClientContract;

class StadiumController extends Controller
{
    public function __construct(
        protected readonly StadiumService $stadiumService,
        protected readonly WeatherApiClientContract $weatherApiClient,
    ) {
    }

    public function index(IndexStadiumsRequest $request): JsonResponse
    {
        $stadiums = $this->stadiumService->getPaginatedList($request->page ?? 1)
            ->map(function (Stadium $stadium) {
                try {
                    $stadium->weather = $this->weatherApiClient->fetchCurrent($stadium->city, $stadium->country);
                } catch (\Throwable $e) {
                    // If we failed to get valid weather data for this stadium, skip it instead of
                    // bombing out the whole request
                }

                return $stadium;
            });

        $results = StadiumResource::collection($stadiums);
        return $results->response();
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
