<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function __invoke(Request $request): Response
    {
        return Inertia::render('dashboard', [
            'copy' => [
                'welcome' => __('Welcome to :game!', ['game' => config('app.name')]),
                'intro' => [
                    __(':game is a video game originally created by :author. It is heavily inspired by Minecraft, and the Pokémon series.', [
                        'game' => config('app.name'),
                        'author' => 'Nilllzz',
                    ]),
                    __(':game focuses on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D. They could even see through the eyes of their trainer.', [
                        'game' => config('app.name'),
                    ]),
                ],
                'documentation' => __('Documentation'),
                'documentationBody' => __(":game has wonderful documentation covering every aspect of the game. Whether you're new to the game or have previous experience, we recommend reading all of the documentation from beginning to end.", ['game' => config('app.name')]),
                'exploreWiki' => __('Explore the wiki'),
                'discord' => __('Discord'),
                'discordBody' => __("We've made it easy for you to get in touch with other players in real-time with our community Discord-server for the game. You can ask questions, share your ideas and even get help from other players. We're always happy to help!"),
                'getDiscord' => __('Get on our Discord server'),
                'customSkin' => __('Custom Skin'),
                'customSkinBody' => __(":game has a built in feature where every player has the opportunity to change their look for multiplayer sessions. You'll be amazed how easily you can change it, store other skin and browse what other have made just at your fingertips.", ['game' => config('app.name')]),
                'getCustomization' => __('Get to customization'),
                'resources' => __('Resources'),
                'resourcesBody' => __('Browse mods, tools, and other community resources created by players. Discover what others have made and share your own creations.'),
                'browseResources' => __('Browse resources'),
                'downloadLabel' => __('Download'),
                'exploreLabel' => __('Explore'),
            ],
            'links' => [
                'wiki' => route('wiki'),
                'discord' => route('discord'),
                'skinHome' => route('skin-home'),
                'resources' => route('resource.index'),
                'download' => route('download'),
            ],
            'author' => [
                'name' => 'Nilllzz',
                'url' => 'https://github.com/nilllzz',
            ],
        ]);
    }
}
