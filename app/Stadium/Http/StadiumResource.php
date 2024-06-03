<?php

namespace App\Stadium\Http;

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
            'location' => [
                'city' => $this->city,
                'country' => $this->country,
                'lat' => $this->lat,
                'long' => $this->long,
            ],
            'weather' => $this->whenNotNull($this->weather, new WeatherResource($this->weather)),
        ];
    }
}
