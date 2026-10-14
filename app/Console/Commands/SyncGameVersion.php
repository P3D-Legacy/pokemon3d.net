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
     * Execute the console command.
     */
    public function handle(): int
    {
        $apiUrl = config('services.github.api_repo');
        if (! is_string($apiUrl) || $apiUrl === '') {
            $this->error('GitHub API URL is not set.');

            return Command::FAILURE;
        }
        $releaseUrl = $apiUrl.'/releases';
        try {
            $response = Http::timeout(5)->get($releaseUrl);
        } catch (\Exception $exception) {
            $this->error('Error getting latest release from GitHub.');
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        if ($response->failed()) {
            $this->error('Error getting latest release from GitHub.');

            return Command::FAILURE;
        }

        $releases = $response->json();

        if (! is_array($releases)) {
            $this->error('Error getting latest release from GitHub.');

            return Command::FAILURE;
        }

        foreach ($releases as $release) {
            $date = new \DateTime($release['published_at']);
            $data = [
                'version' => $release['tag_name'],
                'title' => $release['name'],
                'release_date' => $date,
                'page_url' => $release['html_url'],
                'download_url' => $release['assets'][0]['browser_download_url'],
            ];
            $version = GameVersion::updateOrCreate(['version' => $release['tag_name']], $data);
            $this->info('Updated or created release: '.$version->version);
        }
        $this->info('Done.');

        return Command::SUCCESS;
    }
}
