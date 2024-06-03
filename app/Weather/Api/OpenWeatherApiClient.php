<?php

namespace App\Weather\Api;

use GuzzleHttp\Client as GuzzleClient;

use App\Weather\Api\Exceptions\ApiDataException;
use App\Weather\WeatherData;

class OpenWeatherApiClient implements WeatherApiClientContract
{
    const API_KEY_IDENTIFIER = 'APPID';
    const BASE_URI = 'http://api.openweathermap.org/';
    const CURRENT_WEATHER_BY_CITY_PATH = 'data/2.5/weather';

    const K_TO_C_DIFF = 273.15;

    protected GuzzleClient $client;

    public function __construct(protected readonly string $apiKey)
    {
        $this->client = new GuzzleClient([
            'base_uri' => static::BASE_URI,
        ]);
    }

    /**
     * Override the Guzzle client.
     * 
     * May be used if you already have a Guzzle compatible client you would prefer instead of the provided client,
     * or useful during unit testing to provide mocks.
     * 
     * Returns `$this`, so may be method-chained.
     */
    public function setGuzzleClient(GuzzleClient $guzzleClient): static
    {
        $this->client = $guzzleClient;
        return $this;
    }

    /**
     * Convert from Kelvin to Celsius
     */
    #[Pure]
    protected function kelvinToCelsius(float $kelvin): float
    {
        return $kelvin - static::K_TO_C_DIFF;
    }

    /** @inheritdoc */
    #[Pure]
    public function fetchCurrent(string $city, string $country): WeatherData
    {
        $searchString = "{$city},{$country}";
        $result = $this->client->get(static::CURRENT_WEATHER_BY_CITY_PATH, [
            'query' => [
                static::API_KEY_IDENTIFIER => $this->apiKey,
                'q' => $searchString
            ]
        ]);

        $body = $result->getBody()->getContents();
        $decoded = json_decode($body, flags: JSON_THROW_ON_ERROR);

        $tempKelvin = $decoded->main->temp ?? throw (new ApiDataException())->setMissingData('temp', $searchString);
        $description = $decoded->weather[0]?->description ?? throw (new ApiDataException())->setMissingData('description', $searchString);

        return new WeatherData(
            temp: number_format($this->kelvinToCelsius($tempKelvin), 2),
            description: $description
        );
    }
}
