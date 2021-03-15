<?php

namespace Eduka\Analytics\Models;

use Eduka\Abstracts\EdukaModel;
use Eduka\Analytics\Jobs\GetVisitorGeoData;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;

/**
 * A Visitor is any source.
 */
class Visitor extends EdukaModel
{
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Finds the current contextualized visitor. Logic:.
     *
     * 1. Renders the visitor hash: Hash (ip + user agent).
     * 2. User authenticated ? Connects visitor with user.
     *
     * Always returns a current Visitor model instance, or already a new
     * model instance (already created on the database).
     *
     * @return Eduka\Cube\Models\Visitor
     */
    public static function findMe()
    {
        /*
         * In case the visitor session is already loaded, we don't need
         * to make anything else.
         **/
        if (session('eduka.analytics.visitor.id')) {
            return static::find(session('eduka.analytics.visitor.id'))->first();
        }

        /**
         * The hash is the unique identifier for a visitor.
         * It will not be connected with an authenticated user due to GDPR
         * reasons. It will be encrypted so no one will have access to the
         * data.
         **/
        $hash = md5(request()->ip().'-'.Agent::platform().'-'.Agent::device());

        return Visitor::firstOr(function () use ($hash) {

            /**
             * If the visitor wasn't previously present, then we create
             * a new visitor, and we try to retrieve the visitor
             * ip geo location by calling the job GetVisitorGeoData.
             **/
            $visitor = Visitor::create([
                'hash' => $hash,
            ]);

            GetVisitorGeoData::dispatch($visitor->id, request()->ip());

            session(['eduka.analytics.visitor.id' => $visitor->id]);

            return $visitor;
        });
    }

    /**
     * Adds GeoData from the retrieved visitor IP address, to the contextd
     * visitor model instance.
     *
     * @param array $data
     *
     * @return void
     */
    public function updateGeoData(array $data)
    {
        $this->continent = $data['continent'];
        $this->continentCode = $data['continentCode'];
        $this->country = $data['country'];
        $this->countryCode = $data['countryCode'];
        $this->region = $data['region'];
        $this->regionName = $data['regionName'];
        $this->city = $data['city'];
        $this->district = $data['district'];
        $this->zip = $data['zip'];
        $this->latitude = $data['lat'];
        $this->longitude = $data['lon'];
        $this->timezone = $data['timezone'];
        $this->currency = $data['currency'];

        $this->update();
    }
}
