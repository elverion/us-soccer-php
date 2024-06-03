<?php

namespace App\Stadium\Tests;

use Tests\DatabaseTestCase;

use App\Stadium\{Stadium, StadiumData};
use App\Stadium\Repositories\EloquentStadiumRepository;
use App\Stadium\Repositories\StadiumRepositoryContract;

class EloquentStadiumRepositoryTest extends DatabaseTestCase
{
    /** Allows us to explicitly declare type, and essentially make it
     * constant *after* each test instantiates it.
     */
    protected readonly StadiumRepositoryContract $repo;

    const STADIUM_NAME = 'Test';
    const STADIUM_CITY = 'City';
    const STADIUM_COUNTRY = 'Country';
    const STADIUM_LAT = 123.456;
    const STADIUM_LONG = 567.890;

    /**
     * Create a stadium via the repo, verify it was inserted and properties read back
     */
    public function test_can_create_stadium(): void
    {
        $this->repo = app()->make(EloquentStadiumRepository::class);

        $stadium = $this->repo->create(new StadiumData(
            name: static::STADIUM_NAME,
            city: static::STADIUM_CITY,
            country: static::STADIUM_COUNTRY,
            lat: static::STADIUM_LAT,
            long: static::STADIUM_LONG,
        ));

        // Verify that it was actually inserted into the database
        $this->assertDatabaseHas($stadium->getTable(), [$stadium->getKeyName() => $stadium->getKey()]); // ID check
        $this->assertSame(static::STADIUM_NAME, $stadium->name);
        $this->assertSame(static::STADIUM_CITY, $stadium->city);
        $this->assertSame(static::STADIUM_COUNTRY, $stadium->country);
        $this->assertSame(static::STADIUM_LAT, $stadium->lat);
        $this->assertSame(static::STADIUM_LONG, $stadium->long);
    }

    /**
     * Use updateOrCreate() to both update and create
     */
    public function test_can_update_or_create_stadium(): void
    {
        $this->repo = app()->make(EloquentStadiumRepository::class);

        $data = new StadiumData(
            name: static::STADIUM_NAME,
            city: static::STADIUM_CITY,
            country: static::STADIUM_COUNTRY,
            lat: static::STADIUM_LAT,
            long: static::STADIUM_LONG,
        );
        $origStadium = $this->repo->updateOrCreate($data);
        $this->assertInstanceOf(Stadium::class, $origStadium);
        $this->assertSame(static::STADIUM_LAT, $origStadium->lat);
        $this->assertSame(static::STADIUM_LONG, $origStadium->long);

        $data->lat = 11.11;
        $data->long = 22.22;
        $updatedStadium = $this->repo->updateOrCreate($data);
        $this->assertSame($origStadium->getKey(), $updatedStadium->getKey()); // IDs should match
        $this->assertSame(11.11, $updatedStadium->lat);
        $this->assertSame(22.22, $updatedStadium->long);
    }

    /**
     * Assuming we have a previously-created stadium, can we read it?
     */
    public function test_can_read_stadium(): void
    {
        $this->repo = app()->make(EloquentStadiumRepository::class);

        $stadium = (new Stadium())->forceFill([
            'name' => static::STADIUM_NAME,
            'city' => static::STADIUM_CITY,
            'country' => static::STADIUM_COUNTRY,
            'lat' => static::STADIUM_LAT,
            'long' => static::STADIUM_LONG
        ]);
        $stadium->saveOrFail();

        // Read it from the DB
        $stadiumRead = $this->repo->getById($stadium->getKey());

        // Verify contents against original
        $this->assertSame($stadium->getKey(), $stadiumRead->getKey()); // ID check
        $this->assertSame(static::STADIUM_NAME, $stadiumRead->name);
        $this->assertSame(static::STADIUM_CITY, $stadiumRead->city);
        $this->assertSame(static::STADIUM_COUNTRY, $stadiumRead->country);
        $this->assertSame(static::STADIUM_LAT, $stadiumRead->lat);
        $this->assertSame(static::STADIUM_LONG, $stadiumRead->long);
    }

    /**
     * If updating an existing stadium, verify that the changes take place
     */
    public function test_can_update_stadium(): void
    {
        $this->repo = app()->make(EloquentStadiumRepository::class);

        $stadium = (new Stadium())->forceFill([
            'name' => static::STADIUM_NAME,
            'city' => static::STADIUM_CITY,
            'country' => static::STADIUM_COUNTRY,
            'lat' => static::STADIUM_LAT,
            'long' => static::STADIUM_LONG
        ]);
        $stadium->saveOrFail();

        // Commit change and verify the result we get back matches the wanted updates
        $updates = new StadiumData(
            name: 'New Name',
            city: 'New City',
            country: 'New Country',
            lat: 1.23,
            long: 2.34,
        );
        $stadiumRead = $this->repo->update($stadium->getKey(), $updates);

        // Verify contents against updates
        $this->assertSame($stadium->getKey(), $stadiumRead->getKey()); // ID check
        $this->assertSame($updates->name, $stadiumRead->name);
        $this->assertSame($updates->city, $stadiumRead->city);
        $this->assertSame($updates->country, $stadiumRead->country);
        $this->assertSame($updates->lat, $stadiumRead->lat);
        $this->assertSame($updates->long, $stadiumRead->long);
    }

    /**
     * If updating an existing stadium, verify that the changes take place
     */
    public function test_can_delete_stadium(): void
    {
        $this->repo = app()->make(EloquentStadiumRepository::class);

        $stadium = (new Stadium())->forceFill([
            'name' => static::STADIUM_NAME,
            'city' => static::STADIUM_CITY,
            'country' => static::STADIUM_COUNTRY,
            'lat' => static::STADIUM_LAT,
            'long' => static::STADIUM_LONG
        ]);
        $stadium->saveOrFail();

        // Delete it, and verify it was soft-deleted
        $this->repo->delete($stadium->getKey());

        $this->assertSoftDeleted($stadium->getTable(), [$stadium->getKeyName() => $stadium->getKey()]);
    }
}
