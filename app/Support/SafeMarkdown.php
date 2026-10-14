<?php

namespace App\Support;

use Illuminate\Support\Str;

class SafeMarkdown
{
    public static function html(?string $markdown): string
    {
        if ($markdown === null || $markdown === '') {
            return '';
        }

        return (string) Str::of($markdown)->markdown([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }
}
