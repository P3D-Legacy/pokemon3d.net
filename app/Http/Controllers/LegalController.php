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
        $path = Jetstream::localizedMarkdownPath('terms.md');

        return Inertia::render('terms', [
            'html' => Str::markdown(file_get_contents($path)),
        ]);
    }

    /**
     * Show the privacy policy.
     */
    public function policy(Request $request): Response
    {
        $path = Jetstream::localizedMarkdownPath('policy.md');

        return Inertia::render('policy', [
            'html' => Str::markdown(file_get_contents($path)),
        ]);
    }
}
