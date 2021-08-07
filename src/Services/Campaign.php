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
    public function __construct()
    {
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    public function get()
    {
        $result = new \StdClass();
        $result->name = request()->input('cmpg', null);

        if ($result->name) {
            session(['eduka-analytics-campaign-name' => $result->name]);
        }

        return $result;
    }
}
