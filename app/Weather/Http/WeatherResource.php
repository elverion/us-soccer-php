<?php

namespace App\Weather\Http;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Weather\Weather;

class WeatherResource extends JsonResource
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
            'temp' => $this->temp,
            'description' => $this->description,
        ];
    }
}
