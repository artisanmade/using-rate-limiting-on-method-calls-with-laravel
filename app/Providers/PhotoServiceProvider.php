<?php

namespace App\Providers;

use App\Contracts\PhotoRepository as PhotoRepositoryInterface;
use App\Repositories\Proxies\PhotoRepositoryProxy;
use Illuminate\Support\ServiceProvider;

/**
 * Setup bindings for PhotoRepository in the IoC container.
 *
 * This binding could just as easily be done in the AppServiceProvider,
 * however, for if this was a package there would a dedicated service
 * provider like this one. No short-cuts taken.
 */
class PhotoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // When the container receives a request for the contract
        // it will return the concrete repository instead. This
        // repository should implement the interface.
        $this->app->bind(PhotoRepositoryInterface::class, PhotoRepositoryProxy::class);
    }
}
