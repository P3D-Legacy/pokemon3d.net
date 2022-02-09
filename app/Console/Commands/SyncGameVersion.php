<?php

namespace App\Console\Commands;

use App\Models\GameVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncGameVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:syncrelease';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the latest release from GitHub';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $api_url = env('GITHUB_API_REPO');
        if (!$api_url) {
            $this->error('GitHub API URL is not set.');

            return 1;
        }
        $release_url = env('GITHUB_API_REPO') . '/releases';
        $response = Http::get($release_url)->json();

        foreach ($response as $release) {
            $date = new \DateTime($release['published_at']);
            $data = [
                'version' => $release['tag_name'],
                'title' => $release['name'],
                'release_date' => $date,
                'page_url' => $release['html_url'],
                'download_url' => $release['assets'][0]['browser_download_url'],
            ];
            $version = GameVersion::updateOrCreate(
                ['version' => $release['tag_name']],
                $data
            );
            $this->info('Updated or created release: ' . $version->version);
        }
        $this->info('Done.');
    }
}
