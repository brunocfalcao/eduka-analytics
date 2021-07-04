<?php

namespace Eduka\Analytics\Middleware;

use Closure;
use Illuminate\Http\Request;

class GoalsTracing
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
        foreach (config('eduka-analytics.goals') as $goal) {
            (new $goal)();
        }

        return $next($request);
    }
}
