<?php

namespace App\Weather\Tests;

use App\Weather\Api\OpenWeatherApiClient;
use App\Weather\Api\WeatherApiClientContract;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Mockery\MockInterface;
use Tests\DatabaseTestCase;

class OpenWeatherApiClientTest extends DatabaseTestCase
{
    // Mock data; used so we don't actually try to fetch live data while unit testing.
    const EXAMPLE_WEATHER_LONDON_UK = <<<JSON
    {"coord":{"lon":-0.1257,"lat":51.5085},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04d"}],"base":"stations","main":{"temp":293.05,"feels_like":292.79,"temp_min":291.96,"temp_max":294.42,"pressure":1020,"humidity":65},"visibility":10000,"wind":{"speed":3.6,"deg":300},"clouds":{"all":100},"dt":1717425456,"sys":{"type":2,"id":2075535,"country":"GB","sunrise":1717386445,"sunset":1717445420},"timezone":3600,"id":2643743,"name":"London","cod":200}
    JSON;

    /**
     * Using a mock to avoid hitting a live service (could cause flakey test),
     * attempt to fetch API data as a WeatherData, given the mock's hard-coded
     * weather as 293.05K and overcast clouds.
     */
    public function test_can_fetch_current_weather()
    {
        /** @var GuzzleClient $mockGuzzleClient */
        $mockGuzzleClient = $this->mock(GuzzleClient::class, function (MockInterface $mock) {
            $mock->allows('get')->andReturnUsing(function () {
                return new Response(body: static::EXAMPLE_WEATHER_LONDON_UK);
            });
        });

        $apiClient = (new OpenWeatherApiClient('fake-api-key'))->setGuzzleClient($mockGuzzleClient);
        $data = $apiClient->fetchCurrent('London', 'England');

        $this->assertEquals(19.9, $data->temp);
        $this->assertSame('overcast clouds', $data->description);
    }
}
