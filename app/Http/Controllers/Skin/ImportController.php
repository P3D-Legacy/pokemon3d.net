<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\GJUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function import(Request $request, $id)
    {
        $gjid = Auth::user()->gamejolt->id;
        if ($id != $gjid) {
            session()->flash('flash.bannerStyle', 'danger');
            session()->flash('flash.banner', 'You cannot import this skin!');
            return redirect()
                ->route('skin-home');
        }
        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();
        if ($skincount >= env('SKIN_MAX_UPLOAD')) {
            session()->flash('flash.bannerStyle', 'danger');
            session()->flash('flash.banner', 'You have reached the maximum amount of skins you can upload.');
            return redirect()
                ->route('skins-my');
        }
        $url = 'https://pokemon3d.net/skin/data/' . $id . '.png';
        $valid_types = ['image/png']; // Valid file types
        $client = new Client();
        try {
            $response = $client->get($url);
            if (
                !empty($response->getHeaders()['Content-Type'][0]) &&
                in_array($response->getHeaders()['Content-Type'][0], $valid_types, true)
            ) {
                Storage::disk('player')->put($id . '.png', $response->getBody()->getContents());
            } else {
                session()->flash('flash.bannerStyle', 'danger');
                session()->flash('flash.banner', 'Skin was not in a valid format!');
                return redirect()
                    ->route('skin-home')
                    ->with('error', 'Skin was not in a valid format!');
            }
        } catch (\Exception $e) {
            session()->flash('flash.bannerStyle', 'danger');
            session()->flash('flash.banner', 'Could not find a skin!');
            return redirect()
                ->route('skin-home');
        }
        session()->flash('flash.bannerStyle', 'danger');
        session()->flash('flash.banner', 'Your old skin has been imported!');
        return redirect()
            ->route('skin-home');
    }
}
