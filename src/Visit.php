<?php

namespace Eduka\Analytics;

use Brunocfalcao\Cerebrus\ConcernsSessionPersistence;
use Eduka\Cube\Models\Visit as VisitModel;
use Illuminate\Support\Facades\Route;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Jenssegers\Agent\Facades\Agent;

class Visit
{
    use ConcernsSessionPersistence;

    public function __construct()
    {
        $this->withPrefix('eduka:analytics:visit');
    }

    /**
     * Creates a new visit instance, saves it into the database and
     * places the instance in session for further use.
     *
     * @return void
     */
    public function track()
    {
        $this->overwrite(function () {
            return $this->newInstance();
        });
    }

    /**
     * Instanciates a new visit model, and populates it with all the
     * available visit data.
     *
     * @return Eduka\Models\Visit
     */
    protected function newInstance()
    {
        $visit = new VisitModel();
        $visit->session_id = $this->sessionId();
        $visit->url = request()->fullUrl();
        $visit->path = request()->path();
        $visit->route_name = Route::currentRouteName();
        $visit->ip = $this->ip();

        // Create an unique hashcode.
        $visit->hash = md5(request()->ip().
            Agent::platform().
            Agent::device());

        // Verify if the request is a bot request.
        $CrawlerDetect = new CrawlerDetect;

        // Check the user agent of the current visit source.
        $visit->is_bot = $CrawlerDetect->isCrawler();

        $visit->save();

        return $visit;
    }

    /**
     * Returns the public ip address from a visit. In case it's a localhost
     * ip address, it called the rest API from ipinfo.io to try to translate
     * into a public ip address.
     *
     * @return string
     */
    protected function ip()
    {
        $visit = $this->obtain();

        if ($visit) {
            return $visit->ip;
        }

        try {
            return request()->ip() == '127.0.0.1' ?
                file_get_contents('https://ipinfo.io/ip') :
                request()->ip();
        } catch (\Exception $ex) {
            return request()->ip();
        }
    }
}
