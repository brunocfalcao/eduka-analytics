<?php

namespace Eduka\Analytics\Goals;

use Eduka\Abstracts\EdukaGoal;

class SecondVisit extends EdukaGoal
{
    protected $goal = 'Came back';

    public function compute()
    {
        return $this->visits->count() == 2;
    }
}
