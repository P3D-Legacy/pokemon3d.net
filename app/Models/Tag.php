<?php

namespace App\Models;

use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    public static function getLocale(): string
    {
        return 'en';
    }
}
