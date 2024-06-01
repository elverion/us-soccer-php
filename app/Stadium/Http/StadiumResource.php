<?php

namespace App\Stadium\Http;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Stadium\Stadium;

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
            'id' => $this->id,
        ];
    }
}
