<?php

namespace App\Http\Apis;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * A starting point for any custom API controller.
 *
 * This API is essentially the same thing as the default
 * App\Http\Controllers\Controller but separated for
 * the purposes of making it more RESTful as might be
 * used by API classes. There is basically no difference
 * between an API and a Controller – just their intended
 * use cases vary.
 */
abstract class Api extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
