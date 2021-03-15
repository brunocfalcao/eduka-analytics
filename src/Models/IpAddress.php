<?php

namespace Eduka\Analytics\Models;

use Eduka\Abstracts\EdukaModel;

/**
 * A Visitor is any source.
 */
class IpAddress extends EdukaModel
{
    protected $casts = [
        'is_blacklisted' => 'boolean',
        'is_throttled' => 'boolean',
    ];
}
