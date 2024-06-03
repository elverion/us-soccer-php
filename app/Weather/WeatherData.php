<?php

namespace App\Weather;

use Spatie\LaravelData\Data;

class WeatherData extends Data
{
    public function __construct(
        public readonly float $temp,
        public readonly string $description,
    ) {
    }
}
