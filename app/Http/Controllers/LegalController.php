<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LegalController extends Controller
{
    /**
     * Show the terms of service.
     */
    public function terms(Request $request): Response
    {
        return Inertia::render('terms', $this->legalDocument(
            markdown: 'terms.mdx',
            title: 'Terms and Conditions',
            category: 'Legal',
            updatedAt: '2022-03-24',
        ));
    }

    /**
     * Show the privacy policy.
     */
    public function policy(Request $request): Response
    {
        return Inertia::render('policy', $this->legalDocument(
            markdown: 'policy.mdx',
            title: 'Privacy Policy',
            category: 'Legal',
            updatedAt: '2022-03-24',
        ));
    }

    /**
     * Show the legal information for the application.
     */
    public function legal(Request $request): Response
    {
        return Inertia::render('legal', $this->legalDocument(
            markdown: 'legal.mdx',
            title: 'Legal',
            category: 'Legal',
        ));
    }

    /**
     * Show the contact information for the application.
     */
    public function contact(Request $request): Response
    {
        return Inertia::render('contact', $this->legalDocument(
            markdown: 'contact.mdx',
            title: 'Contact',
            category: 'Contact',
        ));
    }

    /**
     * @return array{title: string, category: string, updatedAt: string, readTime: string}
     */
    private function legalDocument(string $markdown, string $title, string $category, ?string $updatedAt = null): array
    {
        $path = resource_path('markdown/'.$markdown);
        $contents = file_get_contents($path);

        return [
            'title' => $title,
            'category' => $category,
            'updatedAt' => $updatedAt ?? date('Y-m-d', filemtime($path)),
            'readTime' => (string) read_time($contents),
        ];
    }
}
