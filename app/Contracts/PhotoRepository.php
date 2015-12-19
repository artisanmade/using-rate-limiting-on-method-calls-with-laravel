<?php

namespace App\Contracts;

/**
 * A CRUD interface for accessing Photo models using
 * a repository pattern.
 */
interface PhotoRepository
{
    /**
     * Create and return a new Photo model.
     *
     * @param  array $attributes
     *
     * @return \App\Photo
     */
    public function create(array $attributes);
}
