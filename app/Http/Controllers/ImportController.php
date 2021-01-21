<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function import(Request $request, $id)
    {
        $gjid = $request->session()->get('gjid');
        if($id != $gjid) {
            return redirect()->route('home')->with('error', 'You cannot import this skin!');
        }
        $url = 'https://pokemon3d.net/skin/data/'.$id.'.png';
        $valid_types = ['image/png']; // Valid file types
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get($url);
            if (!empty($response->getHeaders()['Content-Type'][0]) && in_array($response->getHeaders()['Content-Type'][0], $valid_types, true)) {
                Storage::disk('skins')->put($id.'.png', $response->getBody()->getContents());
            } else {
                return redirect()->route('home')->with('error', 'Skin was not in a valid format!');
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Could not find a skin!');
        }
        return redirect()->route('home')->with('success', 'Your old skin has been imported!');
    }
}
