<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class LegalController extends Controller
{
    /**
     * Show the terms of service.
     */
    public function terms(Request $request): Response
    {
        return Inertia::render('terms', $this->legalDocument(
            markdown: 'terms.md',
            title: 'Terms and Conditions',
            updatedAt: '2022-03-24',
        ));
    }

    /**
     * Show the privacy policy.
     */
    public function policy(Request $request): Response
    {
        return Inertia::render('policy', $this->legalDocument(
            markdown: 'policy.md',
            title: 'Privacy Policy',
            updatedAt: '2022-03-24',
        ));
    }

    /**
     * @return array{title: string, category: string, updatedAt: string, readTime: string, html: string}
     */
    private function legalDocument(string $markdown, string $title, string $updatedAt): array
    {
        $path = Jetstream::localizedMarkdownPath($markdown);
        $contents = file_get_contents($path);
        $html = Str::markdown($contents);
        $html = preg_replace('/<h1[^>]*>.*?<\/h1>\s*/s', '', $html, 1) ?? $html;

        return [
            'title' => $title,
            'category' => 'Legal',
            'updatedAt' => $updatedAt,
            'readTime' => (string) read_time($contents),
            'html' => $html,
        ];
    }
}
