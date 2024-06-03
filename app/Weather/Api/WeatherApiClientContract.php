<?php

namespace App\Weather\Api;

use App\Weather\Api\Exceptions\ApiDataException;
use App\Weather\WeatherData;

interface WeatherApiClientContract
{
    /**
     * Fetch the current weather for a given city.
     * 
     * @throws ApiDataException If an error occured reading data from external API
     */
    public function fetchCurrent(string $city, string $country): WeatherData;
}
