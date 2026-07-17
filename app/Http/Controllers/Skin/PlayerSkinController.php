<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\GamejoltAccount;
use App\Models\Skin;
use ByteUnits\Binary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PlayerSkinController extends Controller
{
    public function index(): Response
    {
        $playerskins = collect(Storage::disk('player')->files())
            ->filter(fn (string $item): bool => str_contains($item, '.png'))
            ->map(function (string $playerskin): array {
                $gjid = (int) str_replace('.png', '', basename($playerskin));
                $account = GamejoltAccount::query()->find($gjid);

                return [
                    'filename' => basename($playerskin),
                    'gjid' => $gjid,
                    'owner_label' => $account?->username ?? __('Game Jolt ID').': '.$gjid,
                    'image_url' => asset('player/'.basename($playerskin)).'?r='.now()->timestamp,
                    'file_size' => Binary::bytes(Storage::disk('player')->size($playerskin))->format(),
                ];
            })
            ->values()
            ->all();

        return Inertia::render('skins/player', [
            'playerSkins' => $playerskins,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;

        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:png', 'dimensions:ratio=3/4'],
            'rules' => ['accepted'],
        ]);

        $filename = $gjid.'.png';
        $request->file('image')->storeAs(null, $filename, 'player');

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');

        return redirect()->route('skin-home');
    }

    public function duplicate(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $skincount = Auth::user()
            ->gamejolt->skins()
            ->count();

        if ($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        $old_filename = $gjid.'.png';
        $skin = Skin::create([
            'owner_id' => $gjid,
            'user_id' => auth()->user()->id,
            'name' => 'Import: '.$gjid,
        ]);
        $new_filename = $skin->uuid.'.png';
        Storage::disk('skin')->put($new_filename, Storage::disk('player')->get($old_filename));

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was duplicated!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;
        $filename = $gjid.'.png';

        if (! Storage::disk('player')->exists($filename)) {
            return redirect()
                ->route('skin-home')
                ->with('error', 'Skin was not found!');
        }

        Storage::disk('player')->delete($filename);

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was successfully deleted!');
    }

    public function destroyAsAdmin(Request $request, int|string $id): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);

        $filename = $id.'.png';
        if (! Storage::disk('player')->exists($filename)) {
            return redirect()
                ->route('player-skins')
                ->with('error', 'Skin was not found!');
        }

        activity()
            ->causedBy(Auth::user()->gamejolt)
            ->withProperties([
                'filename' => $filename,
                'gjid' => $id,
                'reason' => $request->reason,
            ])
            ->log('deleted');

        Storage::disk('player')->delete($filename);

        return redirect()
            ->route('player-skins')
            ->with('success', 'Skin was successfully deleted!');
    }
}
