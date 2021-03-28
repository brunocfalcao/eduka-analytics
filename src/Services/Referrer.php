<?php

namespace Eduka\Analytics\Services;

class Referrer
{
    public static function __callStatic($method, $args)
    {
        return ReferrerService::new()->{$method}(...$args);
    }
}

class ReferrerService
{
    protected $referrer = null;

    public function __construct()
    {
        $this->referrer = $this->find();
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    public function get()
    {
        return $this->referrer;
    }

    private function find()
    {
        if (request()->headers->get('referer')) {
            return $this->store();
        } elseif (session('eduka.analytics.referrer')) {
            return session('eduka.analytics.referrer');
        }
    }

    private function store()
    {
        session(['eduka.analytics.referrer' => request()->headers->get('referer')]);

        return request()->headers->get('referer');
    }
}
