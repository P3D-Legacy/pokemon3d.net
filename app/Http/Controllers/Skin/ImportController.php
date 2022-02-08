<?php

namespace App\Http\Controllers\Skin;

use App\Models\GJUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(["gj.account"]);
    }

    public function import(Request $request, $id)
    {
        $gjid = Auth::user()->gamejolt->id;
        if ($id != $gjid) {
            return redirect()
                ->route("skin-home")
                ->with("error", "You cannot import this skin!");
        }
        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();
        if ($skincount >= env("SKIN_MAX_UPLOAD")) {
            return redirect()
                ->route("skins-my")
                ->with(
                    "warning",
                    "You have reached the maximum amount of skins you can upload."
                );
        }
        $url = "https://pokemon3d.net/skin/data/" . $id . ".png";
        $valid_types = ["image/png"]; // Valid file types
        $client = new Client();
        try {
            $response = $client->get($url);
            if (
                !empty($response->getHeaders()["Content-Type"][0]) &&
                in_array(
                    $response->getHeaders()["Content-Type"][0],
                    $valid_types,
                    true
                )
            ) {
                Storage::disk("player")->put(
                    $id . ".png",
                    $response->getBody()->getContents()
                );
            } else {
                return redirect()
                    ->route("skin-home")
                    ->with("error", "Skin was not in a valid format!");
            }
        } catch (\Exception $e) {
            return redirect()
                ->route("skin-home")
                ->with("error", "Could not find a skin!");
        }
        return redirect()
            ->route("skin-home")
            ->with("success", "Your old skin has been imported!");
    }
}
