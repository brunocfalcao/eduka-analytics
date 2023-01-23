<?php

namespace Eduka\Analytics\Middleware;

use Closure;
use Eduka\Analytics\Facades\Visit;
use Illuminate\Http\Request;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        Visit::track();

        return $next($request);
    }
}
