<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StadiumTest extends TestCase
{
    const API_ROUTE = '/api/v1/stadiums';

    /**
     * If a valid CSV was provided, we expect a 200 response with enriched data
     */
    public function test_can_hit_stadium_endpoint(): void
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
        $response = $this->postJson(static::API_ROUTE, [
            'stadiums' => $csv,
        ]);

        $response->assertStatus(200);
        // todo: verify resulting data
    }

    /**
     * If a CSV file was not attached, then they should be denied
     */
    public function test_cannot_hit_stadium_endpoint_without_csv_file(): void
    {
        $response = $this->postJson(static::API_ROUTE);

        $response->assertStatus(422); // 422 = Validation error
    }
}
