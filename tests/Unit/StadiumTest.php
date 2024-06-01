<?php

namespace Tests\Unit;

use App\Stadium\Http\StadiumResource;
use PHPUnit\Framework\TestCase;

use App\Stadium\Stadium;
use App\Location\Location;

use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\assertSame;

class StadiumTest extends TestCase
{
    const STADIUM_NAME = "Emirates Stadium";
    const STADIUM_CITY = "London";
    const STADIUM_COUNTRY = "England";
    const STADIUM_LATITUDE = 51.555;
    const STADIUM_LONGITUDE = -0.108611;

    public function test_can_convert_stadium_to_json(): void
    {
        $stadium = new Stadium();
        $stadium->name = static::STADIUM_NAME;
        $stadium->location = new Location(
            city: static::STADIUM_CITY,
            country: static::STADIUM_COUNTRY,
            lat: 51.555,
            long: -0.108611
        );

        $resource = new StadiumResource($stadium);
        $json = $resource->response();

        assertJson($json);
        $decoded = json_decode($json);
        assertSame($decoded->stadium, static::STADIUM_NAME);
    }
}
