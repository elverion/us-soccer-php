<?php

namespace App\Location\Http;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Location\Location;

class LocationResource extends JsonResource
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
            'city' => $this->city,
            'country' => $this->country,
            'lat' => $this->lat,
            'long' => $this->long,
        ];
    }
}
