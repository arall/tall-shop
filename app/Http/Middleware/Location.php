<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Location as LocationHelper;

class Location
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        LocationHelper::setLocation();

        return $next($request);
    }
}
