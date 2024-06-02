<?php

namespace App\Stadium\Tests;

use Tests\DatabaseTestCase;

use App\Stadium\{Stadium, StadiumData, StadiumService};
use App\Stadium\Repositories\EloquentStadiumRepository;

/**
 * This has a lot of overlap with the repository test(s).
 * To prevent wastefully re-checking the same things,
 * this test class will laragely focuse on implicit
 * tests.
 */
class StadiumServiceTest extends DatabaseTestCase
{
    const STADIUM_NAME = 'Test';
    const STADIUM_CITY = 'City';
    const STADIUM_COUNTRY = 'Country';
    const STADIUM_LAT = 123.456;
    const STADIUM_LONG = 567.890;

    public function test_can_create_stadium(): void
    {
        /** @var StadiumService $service */
        $service = app()->make(StadiumService::class);

        $stadium = $service->create(new StadiumData(
            name: static::STADIUM_NAME,
            city: static::STADIUM_CITY,
            country: static::STADIUM_COUNTRY,
            lat: static::STADIUM_LAT,
            long: static::STADIUM_LONG,
        ));

        $this->assertInstanceOf(Stadium::class, $stadium);
    }

    public function test_can_read_stadium(): void
    {
        /** @var StadiumService $service */
        $service = app()->make(StadiumService::class);

        // Set up a stadium to read
        $stadium = (new Stadium())->forceFill([
            'name' => static::STADIUM_NAME,
            'city' => static::STADIUM_CITY,
            'country' => static::STADIUM_COUNTRY,
            'lat' => static::STADIUM_LAT,
            'long' => static::STADIUM_LONG
        ]);
        $stadium->saveOrFail();

        // Read through service and verify
        $stadiumRead = $service->getById($stadium->getKey());
        $this->assertInstanceOf(Stadium::class, $stadiumRead);
        $this->assertSame($stadium->getKey(), $stadiumRead->getKey());
    }

    public function test_can_update_stadium(): void
    {
        /** @var StadiumService $service */
        $service = app()->make(StadiumService::class);

        // Set up a stadium to read
        $stadium = (new Stadium())->forceFill([
            'name' => static::STADIUM_NAME,
            'city' => static::STADIUM_CITY,
            'country' => static::STADIUM_COUNTRY,
            'lat' => static::STADIUM_LAT,
            'long' => static::STADIUM_LONG
        ]);
        $stadium->saveOrFail();

        // Commit change and verify
        $updates = new StadiumData(
            name: 'New Name',
            city: 'New City',
            country: 'New Country',
            lat: 1.23,
            long: 2.34,
        );

        $stadiumRead = $service->update($stadium->getKey(), $updates);
        $this->assertInstanceOf(Stadium::class, $stadiumRead);
        $this->assertSame($stadium->getKey(), $stadiumRead->getKey());
    }

    public function test_can_delete_stadium(): void
    {
        /** @var StadiumService $service */
        $service = app()->make(StadiumService::class);

        // Set up a stadium to read
        $stadium = (new Stadium())->forceFill([
            'name' => static::STADIUM_NAME,
            'city' => static::STADIUM_CITY,
            'country' => static::STADIUM_COUNTRY,
            'lat' => static::STADIUM_LAT,
            'long' => static::STADIUM_LONG
        ]);
        $stadium->saveOrFail();

        // Delete it, and verify it was soft-deleted
        $service->delete($stadium->getKey());
        $this->assertSoftDeleted($stadium->getTable(), [$stadium->getKeyName() => $stadium->getKey()]);
    }
}
