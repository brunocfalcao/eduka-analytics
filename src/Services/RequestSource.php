<?php

namespace Eduka\Analytics\Services;

class RequestSource
{
    public static function __callStatic($method, $args)
    {
        return RequestSourceService::new()->{$method}(...$args);
    }
}

class RequestSourceService
{
    protected Referrer $referrer;
    protected $campaign;

    public function __construct()
    {
        // Initialize referrer and campaign data.
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    public function get()
    {
    }
}
