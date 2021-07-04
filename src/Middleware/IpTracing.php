<?php

namespace Eduka\Analytics\Middleware;

use Closure;
use Eduka\Abstracts\EdukaException;
use Eduka\Analytics\Jobs\CheckIpForBlacklisting;
use Eduka\Analytics\Models\IpAddress;
use Illuminate\Http\Request;

class IpTracing
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
        $record = IpAddress::where('ip_address', ip2())->firstOrNew([
            'ip_address' => ip2(), ]);

        $record->increment('hits');
        $record->save();

        // Local environments don´t need to check for blacklist of throttling.
        if (app()->environment() == 'local') {
            return $next($request);
        }

        if ($record->is_throttled) {
            throw new EdukaException('Sorry, your IP address is throttled. Please wait until it is released, or if not please contact '.env('APP_NAME').' support');
        }

        if ($record->is_blacklisted) {
            throw new EdukaException('Sorry, your IP address ('.ip2().') is blacklisted. Please contact '.env('APP_NAME').' support');
        }

        // Update IP blacklist analysis, if necessary.
        CheckIpForBlacklisting::dispatch(ip2());

        return $next($request);
    }
}
