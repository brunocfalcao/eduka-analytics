<?php

namespace Eduka\Analytics\Middleware;

use Closure;
use Eduka\Analytics\Models\Visit;
use Eduka\Analytics\Models\Visitor;
use Eduka\Analytics\Services\Campaign;
use Eduka\Analytics\Services\Referrer;
use Illuminate\Http\Request;
use Snowplow\RefererParser\Parser;

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
        $visitor = Visitor::findMe();

        $campaign = Campaign::get();

        $referrer = Referrer::get();

        /*
         * Compute the referer.
         **/

        dd($campaign);

        $referrer_url = request()->headers->get('referer');
        $referrer_medium = null;
        $referrer_name = null;
        $referrer_search_terms = null;

        dd($referrer_url);

        if (! empty($referrer_url)) {
            $parser = new Parser();
            $referrer = $parser->parse($referrer_url);

            if ($referrer->isKnown()) {
                $referrer_medium = $referrer->getMedium();
                $referrer_name = $referrer->getSource();
                $referrer_search_terms = $referrer->getSearchTerm();
            }

            // TODO: Create a custom referrers table to include eg Laravel News.
        }

        /**
         * Store visit in database + referrer data if exists.
         **/
        $visit = Visit::create([
            'visitor_id' => $visitor->id,
            'path' => request()->path(),
            'full_path' => request()->fullUrl(),
            'referrer_url' => $referrer_url,
            'referrer_name' => $referrer_name,
            'referrer_medium' => $referrer_medium,
            'referrer_search_terms' => $referrer_search_terms,
            'campaign' => request()->query('cmpgn'),
        ]);

        /*
         * Lets store the visit id in session, in case we need to attach a
         * goal to the visit.
         **/
        session(['eduka.analytics.visit.id' => $visit->id]);

        return $next($request);
    }
}
