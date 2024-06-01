<?php

namespace App\Stadium\Tests;

use App\Stadium\Http\StadiumResource;
use PHPUnit\Framework\TestCase;

use App\Stadium\Stadium;
use App\Location\Location;
use App\Weather\Weather;
use Illuminate\Http\Request;

use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\assertSame;

class StadiumTest extends TestCase
{
    const STADIUM_NAME = "Emirates Stadium";
    const STADIUM_CITY = "London";
    const STADIUM_COUNTRY = "England";
    const STADIUM_LATITUDE = 51.555;
    const STADIUM_LONGITUDE = -0.108611;
    const STADIUM_WEATHER_TEMP = 14.57;
    const STADIUM_WEATHER_DESC = "overcast clouds";

    /**
     * @test
     * Ensures that a Stadium model, plus its location and weather,
     * can be converted to an array via a Resource. This is a
     * prerequisite to rendering to JSON for final output.
     */
    public function test_can_convert_stadium_to_resource_array(): void
    {
        $stadium = new Stadium(
            name: static::STADIUM_NAME,
            location: new Location(
                city: static::STADIUM_CITY,
                country: static::STADIUM_COUNTRY,
                lat: static::STADIUM_LATITUDE,
                long: static::STADIUM_LONGITUDE
            ),
            weather: new Weather(
                temp: static::STADIUM_WEATHER_TEMP,
                description: static::STADIUM_WEATHER_DESC,
            )
        );

        $resource = new StadiumResource($stadium);
        $resArray = $resource->toArray(new Request());

        assertSame(static::STADIUM_NAME, $resArray['stadium']);
        assertSame(static::STADIUM_CITY, $resArray['location']->city);
        assertSame(static::STADIUM_COUNTRY, $resArray['location']->country);
        assertSame(static::STADIUM_LATITUDE, $resArray['location']->lat);
        assertSame(static::STADIUM_LONGITUDE, $resArray['location']->long);
        assertSame(static::STADIUM_WEATHER_TEMP, $resArray['weather']->temp);
        assertSame(static::STADIUM_WEATHER_DESC, $resArray['weather']->description);
    }
}
