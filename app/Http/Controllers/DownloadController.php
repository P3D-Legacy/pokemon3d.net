<?php

namespace App\Http\Controllers;

use App\Models\GameVersion;
use Illuminate\Http\RedirectResponse;

class DownloadController extends Controller
{
    /**
     * Download the latest version of the game
     *
     * @return RedirectResponse
     */
    public function download(): RedirectResponse
    {
        $game_version = GameVersion::latest();
        if ($game_version) {
            return redirect()->to($game_version->download_url);
        }

        return response()->redirectTo('/');
    }
}
