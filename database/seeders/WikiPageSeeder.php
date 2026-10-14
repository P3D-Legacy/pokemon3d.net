<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WikiPage;
use Illuminate\Database\Seeder;

class WikiPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->first() ?? User::factory()->create([
            'name' => 'Wiki Editor',
            'username' => 'wiki-editor',
            'email' => 'wiki-editor@pokemon3d.net',
        ]);

        $pages = [
            [
                'title' => 'Pokémon 3D',
                'slug' => 'pokemon-3d',
                'body' => "## Pokémon 3D\n\nPokémon 3D is a video game originally created by Nilllzz. It is heavily inspired by Minecraft and the Pokémon series.\n\nSee [[Requirements]] before you download.",
            ],
            [
                'title' => 'Requirements',
                'slug' => 'requirements',
                'body' => "## Requirements\n\n* Windows 7 or later\n* Visual C++ Redistributable Runtimes\n* .NET Framework 4.8\n* A graphics card with DirectX 9 support",
            ],
        ];

        foreach ($pages as $page) {
            $record = WikiPage::query()->updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'body' => $page['body'],
                    'published' => true,
                    'user_id' => $user->id,
                ]
            );

            if ($record->revisions()->doesntExist()) {
                $record->recordRevision($user, 'Starter page');
            }
        }
    }
}
