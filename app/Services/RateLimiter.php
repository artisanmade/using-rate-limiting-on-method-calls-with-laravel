<?php

namespace App\Services;

use Illuminate\Contracts\Cache\Repository;

/**
 * A simple hits counter scheme rate limiter that
 * can be used pretty generically by any class.
 */
class RateLimiter
{
    /**
     * This is the prefix that the rate limiter will use
     * when storing hits in the cache. The prefix is
     * prepended to the key used by the cache.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The cache repository where hits are stored.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * The duration that should be used for a timeout when
     * the rate limits are exceeded by too many hits.
     *
     * @var integer
     */
    protected $timeout = 10;

    /**
     * The limits that should be enforced by the rate limiter
     * based on the keys that are stored. The key in the array
     * would correspond to a method or other hit name while the
     * value would be the maximum number of hits allowed.
     *
     * @example [ 'create' => 10 ]
     *
     * @var array
     */
    protected $limits = [];

    /**
     * Inject the cache repository the rate limiter should use
     * and set the limits the rate limiter will use.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param array $limits (optional)
     */
    public function __construct( Repository $cache, array $limits = [] )
    {
        $this->cache = $cache;
        $this->limits = $limits;
    }

    /**
     * Check if the key is rate limited.
     *
     * @param  string $key
     *
     * @return boolean
     */
    public function isLimited($key)
    {
        return in_array(array_keys($this->limits), $key);
    }

    /**
     * Check if the maximum hits has been exceeded for the key.
     *
     * @param  string $key
     *
     * @return boolean
     */
    public function limitExceeded($key)
    {
        return $this->getHits($key) > $this->getMax($key);
    }

    /**
     * Get the maximum hits the key can receive.
     *
     * @param  string $key
     *
     * @return integer
     */
    public function getMax($key)
    {
        return (int) array_get($this->limits, $key, 10);
    }

    /**
     * Get the hits against the cache key.
     *
     * @param  string $key
     *
     * @return integer
     */
    public function getHits($key)
    {
        return $this->cache->get($this->getKey($key), 0);
    }

    /**
     * Increment the hit counter for the cache key.
     *
     * @param string $key
     *
     * @return boolean
     */
    public function addHit($key)
    {
        return $this->cache->increment($this->getKey($key), 1);
    }

    /**
     * Get the fully qualified cache key.
     *
     * @param  string $key
     *
     * @return string
     */
    public function getKey($key)
    {
        return $this->prefix ? $this->prefix . '::' . $key : $key;
    }

    /**
     * Set the prefix that should be used.
     *
     * @param string $prefix
     *
     * @return boolean
     */
    public function setPrefix($prefix)
    {
        return $this->prefix = $prefix;
    }

    /**
     * Get the timeout for when the maximum hits is exceeded.
     *
     * @return integer
     */
    public function getTimeout()
    {
        return (int) $this->timeout;
    }

    /**
     * Set the timeout used when the maximum hits is exceeded.
     *
     * @param integer $timeout
     *
     * @return boolean
     */
    public function setTimeout($timeout)
    {
        return $this->timeout = (int) $timeout;
    }

    /**
     * Get the limits for the rate limiting scheme.
     *
     * @return array
     */
    public function getLimits()
    {
        return $this->limits;
    }

    /**
     * Set the limits for the rate limiting scheme.
     *
     * @param array $limits
     */
    public function setLimits(array $limits)
    {
        return $this->limits = $limits;
    }
}
