<?php

namespace App\Repositories\Proxies;

use App\Exceptions\RateLimitException;
use App\Repositories\PhotoRepository;
use App\Services\RateLimiter;

class PhotoRepositoryProxy
{
    /**
     * The duration that should be used for a timeout when
     * the rate limits are exceeded by too many method calls.
     *
     * @var int
     */
    protected $timeout = 10;

    /**
     * The limits that should be enforced by the rate limiter
     * based on the methods that are called. The key in the array
     * would correspond to a method while the value would be the
     * maximum number of method calls allowed.
     *
     * @example [ 'create' => 10 ]
     *
     * @var array
     */
    protected $limits = [
        'create' => 10,
    ];

    /**
     * The repository that will be forwarded method calls to this class.
     *
     * @var \App\Contracts\PhotoRepository
     */
    protected $instance;

    /**
     * The rate limiter service that will keep track of method calls.
     *
     * @var \App\Services\RateLimiter
     */
    protected $limiter;

    /**
     * Inject the concrete repository instance that should be proxied
     * and the rate limiting service class that will throttle method calls.
     *
     * @param \App\Contracts\PhotoRepository $instance
     * @param \App\Services\RateLimiter      $limiter
     */
    public function __construct(PhotoRepository $instance, RateLimiter $limiter)
    {
        $this->instance = $instance;
        $this->limiter = $limiter;
        $this->boot();
    }

    /**
     * Attempt to forward all calls to this proxy to the underlying instance.
     * Using the rate limiter service, this method will throttle the method
     * calls according to the limits setup by this proxy class.
     *
     * The inner workings of this method have not been abstracted as helper
     * methods which would be a good idea to maintain single responsibility.
     * Basically everything inside the first conditional statement could be
     * abstracted as protected methods on this class.
     *
     * @param string $method     to be called on proxied class
     * @param array  $parameters to be forwarded to proxied class method call
     *
     * @throws \App\Exceptions\RateLimitException when limits are exceeded
     * @throws \BadMethodCallException            when method does not exist on proxied instance
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // Guard against bad method calls.
        if (method_exists($this->instance, $method)) {
            // Only rate limit if the method is set to be.
            if ($this->limiter->isLimited($method)) {
                // Throw an exception if the rate limit has been exceeded.
                if ($this->limiter->limitExceeded($method)) {
                    $callable = get_class($this->instance).'::'.$method;
                    $max = $this->limiter->getMax($method);
                    $timeout = $this->limiter->getTimeout();
                    $message = $callable.'() cannot be called more than '.$max.' in '.$timeout.' minutes.';
                    throw new RateLimitException($message, 429);
                }

                // Increment the hit counter with each call.
                $this->limiter->addHit($method);
            }

            // Forward calls to the underlying instance.
            return call_user_func_array([$this->instance, $method], $parameters);
        }

        // Throw an exception when the underlying instance does not have the method.
        $message = 'Method '.$method.' not implemented on '.get_class($this->instance).'.';
        throw new BadMethodCallException($message);
    }

    /**
     * Setup the proxy by copying the proxy's configurations to
     * the rate limiting service.
     */
    public function boot()
    {
        $this->limiter->setPrefix(get_class($this->instance));
        $this->limiter->setLimits($this->limits);
        $this->limiter->setTimeout($this->timeout);
    }
}
