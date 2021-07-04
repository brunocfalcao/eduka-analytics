<?php

namespace Eduka\Analytics\Services;

use Illuminate\Support\Str;
use Eduka\Analytics\Services\Visitor;
use Eduka\Analytics\Models\Visit as VisitModel;

class Visit
{
    public static function __callStatic($method, $args)
    {
        return VisitService::new()->{$method}(...$args);
    }
}

class VisitService
{
    public function __construct()
    {
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    /**
     * Auto-generates a session id to aggregate the same visits for the same
     * session. As example, if a visitor makes 5 visits (means 5 different
     * urls loaded in the same session) they all get the same session id.
     *
     * @return string|null
     */
    public function session()
    {
        // Autogenerate a session id, and put it in session.
        if (!session('eduka.analytics.visit.session')) {
            $session = Str::random(10);
            session(['eduka.analytics.visit.session' => $session]);

            return $session;
        }

        return session('eduka.analytics.visit.session');
    }

    /**
     * Returns a visit model instance that correspondes to the one that
     * has a session id (normally the last one).
     *
     * @return Eduka\Analytics\Models\Visit
     */
    public function getModelInstance()
    {
        $id = $this->id();
        return VisitModel::firstWhere('id', $id);
    }

    /**
     * Updates the visit session id to the latest one.
     *
     * @param int $id
     * @return void
     */
    public function updateId(int $id)
    {
        session(['eduka.analytics.visit.id' => $id]);
    }

    /**
     * Gets the current last visit session id to whatever needs it be.
     *
     * @return int|null
     */
    public function id()
    {
        return session('eduka.analytics.visit.id', null);
    }

    /**
     * Saves a new visit into the database + referrer + campaign data +
     * routing data.
     *
     * @return Eduka\Analytics\Models\Visit
     */
    public function record()
    {
        $visitor = Visitor::get();

        $referrer = Referrer::get();
        $campaign = Campaign::get();

        $visit = VisitModel::saveWith([
            'session' => $this->session(),
            'visitor_id' => $visitor->id,
            'path' => request()->path(),
            'route_name' => request()->route()->getName(),
            'referrer' => $referrer->name,
            'base_referrer' => $referrer->base,
            'campaign' => $campaign->name,
        ]);

        $this->updateId($visit->id);

        return $visit;
    }
}
