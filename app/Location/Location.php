<?php

namespace App\Location;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function __construct(
        public readonly string $city,
        public readonly string $country,
        public readonly float $lat,
        public readonly float $long,
    ) {
    }
}
