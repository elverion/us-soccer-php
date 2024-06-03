<?php

namespace App\Stadium\Tests;

use App\Stadium\Stadium;
use App\Weather\Api\WeatherApiClientContract;
use App\Weather\WeatherData;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery\MockInterface;
use Tests\DatabaseTestCase;

class StadiumApiTest extends DatabaseTestCase
{
    const API_ROUTE = '/api/v1/stadiums';

    /**
     * Ensure that indexing the stadiums retuns a paginated list of stadiums.
     * We mock the weather so we aren't fetching from a real external API.
     */
    public function test_can_index_stadiums(): void
    {
        /** @var WeatherApiClientContract $mockWeatherApiClient */
        $mockWeatherApiClient = $this->mock(WeatherApiClientContract::class, function (MockInterface $mock) {
            $mock->allows('fetchCurrent')->andReturn(new WeatherData(20.00, 'all good'));
        });

        // Seed some fake stadium data
        $stadiums = Stadium::factory()->count(20)->create();

        $response = $this->getJson(static::API_ROUTE);
        $response->assertOk();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('data.0', function (AssertableJson $json) {
                $json->has('stadium')
                    ->has('location')
                    ->has('location.city')
                    ->has('location.country')
                    ->has('location.lat')
                    ->has('location.long')
                    ->has('weather.temp')
                    ->has('weather.description');
            });
        });
    }

    /**
     * If a valid CSV was provided, we expect a 200 response
     */
    public function test_can_post_stadium_endpoint(): void
    {
        $csv = UploadedFile::fake()->createWithContent(
            'test.csv',
            <<<CSV
Team,FDCOUK,City,Stadium,Capacity,Latitude,Longitude,Country
Arsenal,Arsenal,London,Emirates Stadium,60361,51.555,-0.108611,England
Aston Villa,Aston Villa,Birmingham,Villa Park,42785,52.509167,-1.884722,England
Blackburn Rovers,Blackburn,Blackburn,Ewood Park,31154,53.728611,-2.489167,England
CSV
        );

        $response = $this->postJson(static::API_ROUTE, ['csv' => $csv]);
        $response->assertOk();
    }

    /**
     * If a CSV file was not attached, then they should be denied
     */
    public function test_cannot_post_stadium_endpoint_without_csv_file(): void
    {
        $response = $this->postJson(static::API_ROUTE);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); // 422 = Validation error
    }

    /**
     * If an invalid CSV file was attached, they should receive an error
     */
    public function test_cannot_post_stadium_endpoint_with_invalid_file(): void
    {
        $csv = UploadedFile::fake()->createWithContent(
            'test.csv',
            <<<CSV
            Team,FDCOUK,Stadium,Capacity,Latitude,Longitude,Country
            Arsenal,Emirates Stadium,60361,51.555,-0.108611
            Aston Villa 42785,52.509167
            CSV
        );

        $response = $this->postJson(static::API_ROUTE, ['stadiums' => $csv]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); // 422 = Validation error
    }
}
