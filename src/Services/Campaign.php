<?php

namespace Eduka\Analytics\Services;

class Campaign
{
    public static function __callStatic($method, $args)
    {
        return CampaignService::new()->{$method}(...$args);
    }
}

class CampaignService
{
    protected $campaign = null;

    public function __construct()
    {
        $this->campaign = $this->find();
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    public function get()
    {
        return $this->campaign;
    }

    private function find()
    {
        if (session('eduka.analytics.campaign') !== null) {
            // Session?
            return session('eduka.analytics.campaign');
        } elseif (request()->get('cmpg') !== null) {
            // Request 'cmpg'?
            return $this->store(request()->get('cmpg'));
        } elseif ($request->get('utm_source') !== null) {
            // Request utm_source?
            return $this->store(request()->get('utm_source'));
        }
    }

    private function store(string $source)
    {
        session(['eduka.analytics.campaign' => $source]);

        return $source;
    }
}
