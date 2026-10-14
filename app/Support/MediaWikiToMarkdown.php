<?php

namespace App\Support;

class MediaWikiToMarkdown
{
    public static function convert(string $wikitext): string
    {
        $text = str_replace("\r\n", "\n", $wikitext);
        $text = preg_replace('/<!--.*?-->/s', '', $text) ?? $text;
        $text = preg_replace('/\{\{.*?\}\}/s', '', $text) ?? $text;
        $text = preg_replace('/\{\|.*?\|\}/s', '', $text) ?? $text;

        $text = preg_replace_callback('/^====\s*(.+?)\s*====\s*$/m', fn (array $match): string => '#### '.$match[1], $text) ?? $text;
        $text = preg_replace_callback('/^===\s*(.+?)\s*===\s*$/m', fn (array $match): string => '### '.$match[1], $text) ?? $text;
        $text = preg_replace_callback('/^==\s*(.+?)\s*==\s*$/m', fn (array $match): string => '## '.$match[1], $text) ?? $text;

        $text = preg_replace("/'''''(.*?)'''''/", '***$1***', $text) ?? $text;
        $text = preg_replace("/'''(.*?)'''/", '**$1**', $text) ?? $text;
        $text = preg_replace("/''(.*?)''/", '*$1*', $text) ?? $text;

        $text = preg_replace_callback(
            '/\[\[File:([^\]|]+)(?:\|[^\]]*)?\]\]/',
            fn (array $match): string => '!['.$match[1].']('.$match[1].')',
            $text
        ) ?? $text;

        $text = preg_replace_callback(
            '/\[\[([^\]|#]+)(?:#[^\]|]*)?(?:\|([^\]]+))?\]\]/',
            function (array $match): string {
                $title = trim($match[1]);
                $label = trim($match[2] ?? $title);

                return "[[{$title}|{$label}]]";
            },
            $text
        ) ?? $text;

        $text = preg_replace_callback(
            '/\[(https?:\/\/[^\s\]]+)\s+([^\]]+)\]/',
            fn (array $match): string => '['.$match[2].']('.$match[1].')',
            $text
        ) ?? $text;

        $text = preg_replace('/^\*\s+/m', '- ', $text) ?? $text;
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;

        return trim($text);
    }

    public static function isRedirect(string $wikitext): ?string
    {
        if (preg_match('/^#redirect\s*\[\[([^\]|#]+)(?:#[^\]|]*)?(?:\|[^\]]+)?\]\]/i', trim($wikitext), $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    public static function isMostlyTemplate(string $wikitext): bool
    {
        $withoutComments = preg_replace('/<!--.*?-->/s', '', $wikitext) ?? $wikitext;
        $withoutTemplates = trim(preg_replace('/\{\{.*?\}\}/s', '', $withoutComments) ?? $withoutComments);
        $originalLength = mb_strlen(trim($withoutComments));
        $remainingLength = mb_strlen($withoutTemplates);

        if ($originalLength === 0) {
            return true;
        }

        return $remainingLength < 40 && ($remainingLength / $originalLength) < 0.2;
    }
}
