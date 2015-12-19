<?php

namespace App\Http\Apis;

use App\Contracts\PhotoRepository;
use Illuminate\Http\Request;

/**
 * A RESTful API controller for the Photo model and resources.
 */
class PhotoApi extends Api
{
    /**
     * The repository this API provides RESTful access to.
     *
     * @var \App\Contracts\PhotoRepository
     */
    protected $repository;

    /**
     * Dependency inject the repository for this API.
     *
     * Remember that this type hint is for the interface and
     * not the concrete class. Laravel's IoC will inject the
     * right thing based on PhotoServiceProvider's bindings.
     *
     * @param \App\Contracts\PhotoRepository $repository
     */
    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a new resource in storage.
     *
     * This method uses the underlying repository and can
     * rely on the public interface of the PhotoRepository
     * for creating the Photo model. This makes the API
     * completely ignorant of the Photo model and ensures
     * that the API only has to concern itself with inputs.
     *
     * @param \Illuminate\Http\Request $form data
     *
     * @return \App\Photo
     */
    public function store(Request $form)
    {
        // Here we pass the form data from the API request
        // to the repository as attributes intended to be
        // used when creating the Photo model.
        return $this->repository->create($form->inputs());
    }
}
