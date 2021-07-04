<?php

namespace Eduka\Analytics\Goals;

use Eduka\Abstracts\EdukaGoal;
use Eduka\Analytics\Services\Visit;

class FirstVisit extends EdukaGoal
{
    protected $goal = 'First visit';

    public function compute()
    {
        return $this->visits->get()->count() == 1;
    }
}
