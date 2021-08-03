<?php

namespace Eduka\Analytics\Goals;

use Eduka\Abstracts\EdukaGoal;
use Illuminate\Support\Facades\Route;

/**
 * This goal is triggered when a visitor subscribes to a course in prelaunched
 * mode.
 */
class Subscribed extends EdukaGoal
{
    protected $goal = 'subscribed';
    protected $description = 'visitor has subscribed';

    public function compute()
    {
        if (Route::name() == 'prelaunched.subscribed') {
            $this->attributes = ['email' => request()->input('email')];
            return true;
        }
    }
}
