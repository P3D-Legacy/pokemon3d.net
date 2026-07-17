<?php

namespace App\Http\Controllers;

use App\Models\GameVersion;
use App\Models\Post;
use App\Support\HomeStats;
use App\Support\PostPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(Request $request): Response
    {
        $posts = Post::query()
            ->where('published_at', '<=', now())
            ->where('active', true)
            ->orderBy('sticky', 'desc')
            ->orderBy('published_at', 'desc')
            ->withAnyTags(['Website', 'Game'])
            ->with(['user', 'tags'])
            ->take(4)
            ->get()
            ->map(fn (Post $post): array => PostPresenter::card($post))
            ->values();

        $latestVersion = GameVersion::latest();

        return Inertia::render('home', [
            'posts' => $posts,
            'stats' => HomeStats::all(),
            'screenshots' => [
                ['title' => 'Elms Lab', 'path' => 'img/carousel/Elms_Lab.png', 'author' => 'JappaWakka'],
                ['title' => 'Player House Bedroom', 'path' => 'img/carousel/Player_House_Bedroom.png', 'author' => 'JappaWakka'],
                ['title' => 'Player House Bedroom', 'path' => 'img/carousel/Player_House_Bedroom2.png', 'author' => 'JappaWakka'],
                ['title' => 'Ferry', 'path' => 'img/carousel/Ferry.png', 'author' => 'GhostlyRose'],
                ['title' => 'PokeCenter', 'path' => 'img/carousel/PokeCenter.png', 'author' => 'GhostlyRose'],
                ['title' => 'New Bark Town', 'path' => 'img/carousel/nbt.png', 'author' => 'JappaWakka'],
                ['title' => 'Cerulean Cave', 'path' => 'img/carousel/cerulean_cave.png', 'author' => 'JappaWakka'],
            ],
            'download' => [
                'version' => $latestVersion?->version ?? '0.0.0',
                'released' => $latestVersion
                    ? (now()->subYear() > $latestVersion->release_date
                        ? $latestVersion->release_date->isoFormat('LL')
                        : $latestVersion->release_date->diffForHumans())
                    : 'Never',
            ],
            'copy' => [
                'headline' => __('Old school Pokémon in a 3D world!'),
                'subheadline' => __('Bringing the games from the early generation of Pokémon games to the modern era.'),
                'latestNews' => __('Latest news'),
                'readMore' => __('Read more blog posts'),
                'nothingToShow' => __('There is nothing to show'),
                'goToBlog' => __('Go to blog'),
                'screenshots' => __('Screenshots'),
                'historyTitle' => __('History'),
                'history' => [
                    __(':game is a video game originally created by :author. It is heavily inspired by Minecraft, and the Pokémon series.', [
                        'game' => config('app.name'),
                        'author' => '<a href="https://github.com/nilllzz" class="text-green-100 hover:underline hover:text-white">Nilllzz</a>',
                    ]),
                    __(':game focuses on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D. They could even see through the eyes of their trainer.', [
                        'game' => config('app.name'),
                    ]),
                ],
                'featuresTitle' => __('Features'),
                'nostalgiaTitle' => __('Nostalgia'),
                'nostalgiaBody' => __('Remember the old days when you were playing on a GameBoy? If so; you should try out this game and awake your inner child.'),
                'generationsTitle' => __('Most Generations and Regions'),
                'generationsBody' => __(':game will have support for all generations of Pokémon in the future and all 2D regions will be accessible in the game.', [
                    'game' => config('app.name'),
                ]),
                'experienceTitle' => __('A New Experience'),
                'experienceBody' => __(':game focuses on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D. They could even see through the eyes of their trainer.', [
                    'game' => config('app.name'),
                ]),
                'mediaTitle' => __('Media'),
                'ctaTitle' => __('Miss your childhood?'),
                'ctaSubtitle' => __('Go back in time with :game!', ['game' => config('app.name')]),
                'downloadLabel' => __('Download'),
                'releasedLabel' => __('Released'),
                'requirementsLabel' => __('Requirements apply'),
            ],
        ]);
    }

    /**
     * Show the legal information for the application.
     */
    public function legal(Request $request): Response
    {
        $localizedMarkdownPath = Jetstream::localizedMarkdownPath('legal.md');

        return Inertia::render('legal', [
            'html' => Str::markdown(file_get_contents($localizedMarkdownPath)),
        ]);
    }

    /**
     * Show the contact information for the application.
     */
    public function contact(Request $request): Response
    {
        $localizedMarkdownPath = Jetstream::localizedMarkdownPath('contact.md');

        return Inertia::render('contact', [
            'html' => Str::markdown(file_get_contents($localizedMarkdownPath)),
        ]);
    }
}
