<?php

namespace Eduka\Analytics\Middleware;

use Closure;
use Eduka\Analytics\Models\Visit;
use Eduka\Analytics\Models\Visitor;
use Eduka\Analytics\Services\Campaign;
use Eduka\Analytics\Services\Referrer;
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
        Referrer::refresh();

        Visit::store();

        return $next($request);
    }
}
