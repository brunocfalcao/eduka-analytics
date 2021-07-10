<?php

namespace Eduka\Analytics\Middleware;

use Closure;
use Eduka\Analytics\Services\Referrer;
use Eduka\Analytics\Services\Visit;
use Illuminate\Http\Request;

class VisitTracing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Refresh referrer, in case it exists.
        Referrer::refresh();

        // Record the visit + GeoIP operations.
        Visit::record();

        return $next($request);
    }
}
