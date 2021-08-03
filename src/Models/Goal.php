<?php

namespace Eduka\Analytics\Models;

use Eduka\Abstracts\EdukaModel;

class Goal extends EdukaModel
{
    protected $casts = [
        'attributes' => 'array',
    ];
}
