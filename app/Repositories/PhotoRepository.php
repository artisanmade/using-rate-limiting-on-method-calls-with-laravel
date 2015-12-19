<?php

namespace App\Repositories;

use App\Contracts\PhotoRepository as PhotoRepositoryInterface;
use App\Photo;

/**
 * Repository pattern implementation of PhotoRepository interface.
 *
 * The interface is used to ensure that repository adheres to
 * pattern and contract at runtime (checked via PHP).
 */
class PhotoRepository implements PhotoRepositoryInterface
{
    /**
     * The Eloquent model this repository accesses.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Dependency inject the Eloquent model for this repository.
     *
     * @param \App\Photo $model
     */
    public function __construct(Photo $model)
    {
        $this->model = $model;
    }

    /**
     * Create and return a new Photo model.
     *
     * @param  array $attributes
     *
     * @return \App\Photo
     */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance();
        $model->fill($attributes);
        $model->save();

        return $model;
    }
}
