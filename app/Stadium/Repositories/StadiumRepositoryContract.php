<?php

namespace App\Stadium\Repositories;

use App\Stadium\Stadium;
use App\Stadium\StadiumData;

interface StadiumRepositoryContract
{
    /**
     * Create a new stadium
     * 
     * Returns the newly-created `Stadium` on success.
     * 
     * @throws Exception If a database error occurred
     */
    public function create(StadiumData $data): Stadium;

    /**
     * Read and return a stadium (if it exists), or return `null` if not found
     */
    public function getById(int $id): ?Stadium;

    /**
     * Update a stadium, and return the post-updated stadium
     */
    public function update(int $id, StadiumData $data): Stadium;

    /**
     * (Soft-)delete a stadium
     */
    public function delete(int $id): void;
}
