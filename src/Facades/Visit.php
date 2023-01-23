<?php

namespace Eduka\Analytics\Facades;

use Illuminate\Support\Facades\Facade;

class Visit extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'eduka-visit';
    }
}
