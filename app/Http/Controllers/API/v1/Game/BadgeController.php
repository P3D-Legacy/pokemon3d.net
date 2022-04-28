<?php

namespace App\Http\Controllers\API\v1\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @group Game - Badges
 *
 * APIs for getting badges from the game
 */
class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @response {
     *      "boulder": {
     *          "name": "Boulder",
     *          "image": "https://pokemon3d.net/img/badge/Boulder.png"
     *      },
     *      "cascade": {
     *          "name": "Cascade",
     *          "image": "https://pokemon3d.net/img/badge/Cascade.png"
     *      },
     * }
     */
    public function index(): JsonResponse
    {
        $badges = array();
        $folder_path = 'img/badge/';
        $files = File::allFiles(public_path($folder_path));
        foreach ($files as $file) {
            $filename = str_replace('.png', '', $file->getFilename());
            $name = str_replace('_', ' ', $filename);
            $badges[Str::slug($filename, '_')] = array(
                'name' => $name,
                'image' => url($folder_path . $file->getFilename()),
            );
        }
        return response()->json(array(
            'data' => $badges,
        ));
    }

}
