<?php

namespace App\Support;

class WikiLinkRenderer
{
    public static function render(string $markdown): string
    {
        return preg_replace_callback(
            '/\[\[([^\]|]+)(?:\|([^\]]+))?\]\]/',
            function (array $matches): string {
                $title = trim($matches[1]);
                $label = trim($matches[2] ?? $title);

                return '['.$label.']('.WikiEntryResolver::urlForTitle($title).')';
            },
            $markdown
        ) ?? $markdown;
    }
}
