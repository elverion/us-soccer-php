<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StadiumTest extends TestCase
{
    const API_ROUTE = '/api/v1/stadiums';

    /**
     * 
     */
    public function test_can_hit_stadium_endpoint(): void
    {
        $response = $this->post(static::API_ROUTE);

        $response->assertStatus(200);
    }
}
