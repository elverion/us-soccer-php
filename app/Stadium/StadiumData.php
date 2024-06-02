<?php

namespace App\Stadium;

use Spatie\LaravelData\Data;

class StadiumData extends Data
{
    public function __construct(
        public string $name,
        public string $city,
        public string $country,
        public float $lat,
        public float $long,
    ) {
    }
}
