<?php

namespace Eduka\Analytics;

use Eduka\Abstracts\Classes\EdukaServiceProvider;

class AnalyticsServiceProvider extends EdukaServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        /**
         * Bind facades.
         */
        $this->app->bind('eduka-visit', function () {
            return new Visit();
        });
    }
}
