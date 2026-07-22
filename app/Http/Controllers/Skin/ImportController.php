<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Support\SkinStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ImportController extends Controller
{
    public function import(Request $request, int|string $id): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;

        if ((int) $id !== (int) $gjid) {
            session()->flash('flash.bannerStyle', 'danger');
            session()->flash('flash.banner', 'You cannot import this skin!');

            return redirect()->route('skin-home');
        }

        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();

        if ($skincount >= config('skins.max_upload')) {
            session()->flash('flash.bannerStyle', 'warning');
            session()->flash('flash.banner', 'You have reached the maximum amount of skins you can upload.');

            return redirect()->route('skins-my');
        }

        $url = rtrim((string) config('skins.import_base_url'), '/').'/'.$id.'.png';
        $maxBytes = (int) config('skins.import_max_bytes');

        try {
            $response = Http::timeout(15)
                ->withOptions(['stream' => false])
                ->withHeaders(['Accept' => 'image/png'])
                ->get($url);

            if (! $response->successful()) {
                session()->flash('flash.bannerStyle', 'danger');
                session()->flash('flash.banner', 'Could not find a skin!');

                return redirect()->route('skin-home');
            }

            $contents = $response->body();
            $contentType = strtolower((string) $response->header('Content-Type'));

            if (strlen($contents) === 0 || strlen($contents) > $maxBytes) {
                session()->flash('flash.bannerStyle', 'danger');
                session()->flash('flash.banner', 'Skin was not in a valid format!');

                return redirect()
                    ->route('skin-home')
                    ->with('error', 'Skin was not in a valid format!');
            }

            $isPngType = str_contains($contentType, 'image/png') || $contentType === '';
            if (! $isPngType || ! SkinStorage::isValidPng($contents)) {
                session()->flash('flash.bannerStyle', 'danger');
                session()->flash('flash.banner', 'Skin was not in a valid format!');

                return redirect()
                    ->route('skin-home')
                    ->with('error', 'Skin was not in a valid format!');
            }

            SkinStorage::putPlayer($id, $contents);
        } catch (\Exception) {
            session()->flash('flash.bannerStyle', 'danger');
            session()->flash('flash.banner', 'Could not find a skin!');

            return redirect()->route('skin-home');
        }

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', 'Your old skin has been imported!');

        return redirect()->route('skin-home');
    }
}
