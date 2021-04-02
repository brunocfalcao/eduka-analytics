<?php

namespace Eduka\Analytics\Models;

use Eduka\Abstracts\EdukaModel;
use Eduka\Analytics\Models\Visitor;
use Eduka\Analytics\Services\Campaign;
use Eduka\Analytics\Services\Referrer;

class Visit extends EdukaModel
{
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function session()
    {
        session(['eduka.analytics.visit.id' => $this->id]);
    }

    public static function store()
    {
        $visitor = Visitor::locate();

        $referrer = Referrer::get();
        $campaign = Campaign::get();

        return static::create([
            'visitor_id' => $visitor->id,
            'path' => request()->path(),
            'full_path' => request()->fullUrl(),
            'referrer' => $referrer->name,
            'base_referrer' => $referrer->base,
            'campaign' => $campaign->name,
        ]);
    }
}
