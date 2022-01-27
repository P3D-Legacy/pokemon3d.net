<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GitHubHelper
{
    private $githubData;

    public function __construct()
    {
        $this->githubData = Cache::get("github_current_version", function () {
                $ApiUrl = env('GITHUB_API_REPO').'/releases/latest';
                $response = Http::withHeaders([
                    'X-First' => 'foo',
                    'X-Second' => 'bar'
                ])->get($ApiUrl);
                $decodedResponse = json_decode($response, true);
                $date = new \DateTime($decodedResponse['published_at']);
                $data = [
                    'version' => $decodedResponse['tag_name'],
                    'version_title' => $decodedResponse['name'],
                    'release_date' => $date,
                    'release_page' => $decodedResponse['html_url'],
                    'downloadable_url' => $decodedResponse['assets'][0]['browser_download_url'],
                    'author' => [
                        'name' => $decodedResponse['author']['login'],
                        'avatar' => $decodedResponse['author']['avatar_url'],
                        'profile' => $decodedResponse['author']['html_url']
                    ]
                ];
                Cache::put('github_current_version', $data, 60*60);
                return $data;
            });
    }

    public function getAll()
    {
        return $this->githubData;
    }

    public function getVersion()
    {
        return $this->githubData["version"];
    }

    public function getVersionTitle()
    {
        return $this->githubData["version_title"];
    }

    public function getReleaseDate()
    {
        return $this->githubData["release_date"];
    }

    public function getDownloadUrl()
    {
        return $this->githubData["downloadable_url"];
    }

    public function getAuthor()
    {
        return $this->githubData["author"];
    }

    public function getAuthorName()
    {
        return $this->githubData["author"]["name"];
    }

    public function getAuthorAvatar()
    {
        return $this->githubData["author"]["avatar"];
    }

    public function getAuthorUrl()
    {
        return $this->githubData["author"]["profile"];
    }
}
