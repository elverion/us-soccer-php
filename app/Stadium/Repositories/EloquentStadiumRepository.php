<?php

namespace App\Stadium\Repositories;

use App\Stadium\Stadium;
use App\Stadium\StadiumData;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentStadiumRepository implements StadiumRepositoryContract
{
    // Note to reviewer(s): This model isn't *really* used itself,
    // it is only used to inform which class this repo operates off of
    public function __construct(private readonly Stadium $model)
    {
    }

    /** @inheritdoc */
    public function create(
        StadiumData $data,
    ): Stadium {
        return $this->model->forceCreate($data->all());
    }

    /** @inheritdoc */
    public function updateOrCreate(
        StadiumData $data,
    ): Stadium {
        return $this->model
            ->newModelQuery()
            ->updateOrCreate(
                ['name' => $data->name, 'city' => $data->city, 'country' => $data->country],
                $data->toArray()
            );
    }

    /** @inheritdoc */
    public function getById(int $id): ?Stadium
    {
        return $this->model->find($id);
    }

    /** @inheritdoc */
    public function update(int $id, StadiumData $data): Stadium
    {
        $stadium = $this->getById($id);
        $stadium->forceFill($data->all());
        $stadium->saveOrFail();

        return $stadium;
    }

    /** @inheritdoc */
    public function delete(int $id): void
    {
        $this->model->delete($id);
    }

    /** @inheritdoc */
    public function getPaginatedList(int $page, int $perPage): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->paginate(page: $page, perPage: $perPage);
    }
}
