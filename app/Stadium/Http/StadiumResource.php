<?php

namespace App\Stadium\Http;

use App\Location\Http\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Stadium\Stadium;
use App\Weather\Http\WeatherResource;

class StadiumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Stadium $this */

        return [
            'stadium' => $this->name,
            'location' => new LocationResource($this->location),
            'weather' => new WeatherResource($this->weather),
        ];
    }
}
