<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $posts = Post::where('published_at', '<=', now())
            ->where('active', true)
            ->orderBy('sticky', 'desc')
            ->orderBy('published_at', 'desc')
            ->withAnyTags(['Website', 'Game'])
            ->take(4)
            ->get();

        return view('home', compact('posts'));
    }

    /**
     * Show the legal information for the application.
     */
    public function legal(Request $request): \Illuminate\View\View
    {
        $localizedMarkdownPath = Jetstream::localizedMarkdownPath('legal.md');

        return view('legal', [
            'legal' => Str::markdown(file_get_contents($localizedMarkdownPath)),
        ]);
    }

    /**
     * Show the contact information for the application.
     */
    public function contact(Request $request): \Illuminate\View\View
    {
        $localizedMarkdownPath = Jetstream::localizedMarkdownPath('contact.md');

        return view('contact', [
            'contact' => Str::markdown(file_get_contents($localizedMarkdownPath)),
        ]);
    }
}
