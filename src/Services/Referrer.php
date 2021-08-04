<?php

namespace Eduka\Analytics\Services;

use Eduka\Pathfinder\Pathfinder;

class Referrer
{
    public static function __callStatic($method, $args)
    {
        return ReferrerService::new()->{$method}(...$args);
    }
}

class ReferrerService
{
    public function __construct()
    {
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    public function refresh()
    {
        // Can be a HTTP_REFERER or the ?utm_source=.
        $referer = request()->header('referer', null);
        $utm_source = request()->input('utm_source', null);

        if ($referer && Pathfinder::isExternal()) {
            // We do have a referrer header. It will be the first time.
            session(['eduka.analytics.referrer.name' => $referer]);
            session(['eduka.analytics.referrer.base' => $this->host()]);
            session(['eduka.analytics.referrer.first-request' => true]);

            return;
        }

        if (request()->input('utm_source', null)) {
            // We do have an utm_source querystring, for the first time.
            session(['eduka.analytics.referrer.name' => $utm_source]);
            session(['eduka.analytics.referrer.base' => $this->host()]);
            session(['eduka.analytics.referrer.first-request' => true]);

            return;
        }

        if ($this->onSession()) {
            // It already existed, so it will not be a first request.
            session(['eduka.analytics.referrer.first-request' => false]);

            return;
        }
    }

    public function get()
    {
        $result = new \StdClass();

        $result->name = session('eduka.analytics.referrer.name');
        $result->base = session('eduka.analytics.referrer.base');
        $result->firstRequest = session('eduka.analytics.referrer.first-request');

        return $result;
    }

    protected function onSession()
    {
        return (bool) session('eduka.analytics.referrer.name');
    }

    protected function host()
    {
        $parts = parse_url(request()->fullUrl());

        if ($parts === false || ! isset($parts['host'])) {
            return '';
        }

        return $parts['host'];
    }
}
