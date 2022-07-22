<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GameVersion;
use App\Models\Post;
use App\Models\Resource;
use App\Models\ResourceUpdate;
use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Admin Lastname',
            'email' => 'team@pokemon3d.net',
            'username' => 'admin',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        User::factory(10)->create();
        Post::factory(10)->create();
        GameVersion::factory(10)->create();
        Category::factory(5)->create();
        Resource::factory(25)
            ->has(Category::factory()->count(1))
            ->has(ResourceUpdate::factory()->count(3), 'updates')
            ->create();
        Server::factory(10)->create();
    }
}
