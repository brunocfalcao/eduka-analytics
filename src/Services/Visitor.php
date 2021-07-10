<?php

namespace Eduka\Analytics\Services;

use Eduka\Analytics\Jobs\GetVisitorGeoData;
use Eduka\Analytics\Models\Visitor as VisitorModel;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;

class Visitor
{
    public static function __callStatic($method, $args)
    {
        return VisitorService::new()->{$method}(...$args);
    }
}

class VisitorService
{
    public function __construct()
    {
    }

    public static function new(...$args)
    {
        return new self(...$args);
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
    public function get()
    {
        /*
         * In case the visitor session is already loaded, we don't need
         * to make anything else.
         **/
        if (session('eduka.analytics.visitor.id')) {
            if (optional(VisitorModel::find(session('eduka.analytics.visitor.id')))->first()) {
                return VisitorModel::find(session('eduka.analytics.visitor.id'))->first();
            }
        }

        /**
         * The hash is the unique identifier for a visitor.
         * It will not be connected with an authenticated user due to GDPR
         * reasons. It will be encrypted so no one will have access to the
         * data.
         **/
        $hash = md5(request()->ip().'-'.Agent::platform().'-'.Agent::device());

        return VisitorModel::where('hash', $hash)->firstOr(function () use ($hash) {

            /**
             * If the visitor wasn't previously present, then we create
             * a new visitor, and we try to retrieve the visitor
             * ip geo location by calling the job GetVisitorGeoData.
             **/
            $visitor = VisitorModel::saveWith([
                'hash' => $hash,
            ]);

            GetVisitorGeoData::dispatch($visitor->id, ip2());

            session(['eduka.analytics.visitor.id' => $visitor->id]);

            return $visitor;
        });
    }
}
