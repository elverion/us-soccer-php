<?php

namespace App\Stadium\Repositories;

use Illuminate\Database\QueryException;

use App\Stadium\Stadium;
use App\Stadium\StadiumData;
use Illuminate\Pagination\LengthAwarePaginator;

interface StadiumRepositoryContract
{
    /**
     * Create a new stadium
     * 
     * Returns the newly-created `Stadium` on success.
     * 
     * @throws QueryException If a database error occurred
     */
    public function create(StadiumData $data): Stadium;

    /**
     * Create a new stadium, or update an stadium if it already exists.
     * A stadium is assumed to be the same if it has the same name,
     * city, and country. If you need to update the name, city,
     * or country for a stadium, use the `update()` method instead.
     * 
     * @throws QueryException If a database error occurred
     */
    public function updateOrCreate(StadiumData $data): Stadium;

    /**
     * Read and return a stadium (if it exists), or return `null` if not found
     * 
     * @throws QueryException If a database error occurred
     */
    public function getById(int $id): ?Stadium;

    /**
     * Update a stadium, and return the post-updated stadium
     * 
     * @throws QueryException If a database error occurred
     */
    public function update(int $id, StadiumData $data): Stadium;

    /**
     * (Soft-)delete a stadium
     * 
     * @throws QueryException If a database error occurred
     */
    public function delete(int $id): void;


    /**
     * Return paginated results
     * 
     * @throws QueryException If a database error occurred
     */
    public function getPaginatedList(int $page, int $perPage): LengthAwarePaginator;
}
