<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use League\HTMLToMarkdown\HtmlConverter;
use Tumblr\API\Client;

class ImportFromTumblr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:tumblr {blogname : The name of the Tumblr blog}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import posts from a Tumblr blog';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $blogName = $this->argument('blogname');

        $credentials = config('services.tumblr');

        if (! $this->hasValidCredentials($credentials)) {
            $this->error('Please set the tumblr environment variables.');

            return 1;
        }

        try {
            $client = new Client(
                $credentials['consumer_key'],
                $credentials['consumer_secret'],
                $credentials['oauth_token'],
                $credentials['oauth_token_secret']
            );

            $posts = $client->getBlogPosts($blogName);

            if (! is_iterable($posts->posts ?? null)) {
                $this->error('Tumblr returned an invalid posts response.');

                return Command::FAILURE;
            }

            foreach ($posts->posts as $post) {
                $body = preg_replace("/\r|\n/", '', trim(strip_tags($post->body))); // Clean up the body
                $postedBy = substr($body, -30); // Get the last 30 characters of the body
                if (str_contains($postedBy, '-')) {
                    // If the last 30 characters contain a dash, then it's probably the author
                    $postedBy = substr($postedBy, strpos($postedBy, '-')); // Get the author
                    $postedBy = trim(str_replace('-', '', $postedBy)); // Remove the dash
                    $postedBy = explode('//', $postedBy)[0]; // Get the first part of the author
                } elseif (str_contains($postedBy, '//')) {
                    // If the last 30 characters contain a double slash, then it's probably the author
                    $postedBy = substr($postedBy, strpos($postedBy, '//')); // Get the author
                    $postedBy = str_replace('/', '', $postedBy); // Remove the slash
                    $postedBy = str_replace('&lsquo;', '', $postedBy); // Remove the left single quote
                    $postedBy = str_replace('&rsquo;', '', $postedBy); // Remove the right single quote
                }
                $postedBy = str_replace(' ', '', $postedBy); // Remove the spaces
                $user = User::where('username', $postedBy)->first() ?? User::first(); // Try to find the user by username or default to the first user
                $title = str_replace('Pokémon3D ', '', $post->title); // Remove the Pokémon3D prefix from the title
                $title = str_replace('!', '', $title); // Remove the exclamation mark
                $title = ucfirst($title); // Capitalize the first letter of the title
                $this->info('Title: '.$title.', Posted by: '.$user->username);
                $converter = new HtmlConverter;
                $markdown = $converter->convert($post->body);
                Post::create([
                    'title' => $title,
                    'body' => $markdown,
                    'slug' => $post->slug,
                    'published_at' => Carbon::parse($post->date),
                    'user_id' => $user->id,
                    'active' => true,
                    'sticky' => false,
                ]);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return Command::SUCCESS;
    }

    private function hasValidCredentials(mixed $credentials): bool
    {
        if (! is_array($credentials)) {
            return false;
        }

        foreach (['consumer_key', 'consumer_secret', 'oauth_token', 'oauth_token_secret'] as $key) {
            if (! isset($credentials[$key]) || ! is_string($credentials[$key]) || trim($credentials[$key]) === '') {
                return false;
            }
        }

        return true;
    }
}
