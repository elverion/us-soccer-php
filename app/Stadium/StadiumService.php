<?php

namespace App\Stadium;

use App\Stadium\Repositories\StadiumRepositoryContract;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Note to reviewers: This class seems pretty dumb at first glance
 * since it is just forwarding things through to the repo.
 * 
 * The intention is that this is where business logic can be attached,
 * such that we are separating database concerns and business logic.
 */
class StadiumService
{
    public function __construct(
        public readonly StadiumRepositoryContract $stadiumRepository
    ) {
    }

    public function create(
        StadiumData $data,
    ): Stadium {
        return $this->stadiumRepository->create($data);
    }

    public function updateOrCreate(
        StadiumData $data,
    ): Stadium {
        return $this->stadiumRepository->updateOrCreate($data);
    }

    public function getById(int $id): ?Stadium
    {
        return $this->stadiumRepository->getById($id);
    }

    public function update(int $id, StadiumData $data): Stadium
    {
        return $this->stadiumRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $this->stadiumRepository->delete($id);
    }

    public function getPaginatedList(int $page, int $perPage = 10): LengthAwarePaginator
    {
        return $this->stadiumRepository->getPaginatedList($page, $perPage);
    }
}
