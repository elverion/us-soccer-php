<?php

namespace App\Location;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function __construct(
        public string $city,
        public string $country,
        public float $lat,
        public float $long,
    ) {
    }
}
