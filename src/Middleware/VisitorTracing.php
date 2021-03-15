<?php

namespace Eduka\Analytics\Middleware;

use Closure;
use Eduka\Analytics\Models\Visitor;
use Illuminate\Http\Request;

class VisitorTracing
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
        $visitor = Visitor::findMe();

        return $next($request);
    }
}
