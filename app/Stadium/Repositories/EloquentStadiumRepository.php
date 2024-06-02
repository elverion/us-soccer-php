<?php

namespace App\Stadium\Repositories;

use App\Stadium\Stadium;
use App\Stadium\StadiumData;

class EloquentStadiumRepository implements StadiumRepositoryContract
{
    // Note to reviewer(s): This model isn't *really* used itself,
    // it is only used to inform which class this repo operates off of
    public function __construct(private readonly Stadium $model)
    {
    }

    public function create(
        StadiumData $data,
    ): Stadium {
        return $this->model->forceCreate($data->all());
    }

    public function getById(int $id): ?Stadium
    {
        return $this->model->find($id);
    }

    public function update(int $id, StadiumData $data): Stadium
    {
        $stadium = $this->getById($id);
        $stadium->forceFill($data->all());
        $stadium->saveOrFail();

        return $stadium;
    }

    public function delete(int $id): void
    {
        $this->model->delete($id);
    }
}
