<?php

namespace Eduka\Analytics\Models;

use Eduka\Abstracts\EdukaModel;

class Visit extends EdukaModel
{
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
