<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\GamejoltAccount;
use App\Models\Skin;
use App\Support\SkinStorage;
use ByteUnits\Binary;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use League\Flysystem\UnableToReadFile;
use RuntimeException;

class PlayerSkinController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:skin-player-destroy', only: ['index', 'destroyAsAdmin']),
        ];
    }

    public function index(): Response
    {
        $playerskins = collect(SkinStorage::playerFiles())
            ->map(function (string $playerskin): array {
                $gjid = (int) str_replace('.png', '', basename($playerskin));
                $account = GamejoltAccount::query()->find($gjid);

                return [
                    'filename' => basename($playerskin),
                    'gjid' => $gjid,
                    'owner_label' => $account?->username ?? __('Game Jolt ID').': '.$gjid,
                    'image_url' => SkinStorage::urlPlayer($gjid),
                    'file_size' => ($size = SkinStorage::sizePlayer($gjid)) !== null
                        ? Binary::bytes($size)->format()
                        : 'N/A',
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
            'image' => SkinStorage::imageValidationRules(),
            'rules' => ['accepted'],
        ]);

        try {
            SkinStorage::storePlayer($request->file('image'), $gjid);
        } catch (RuntimeException) {
            return redirect()
                ->route('skin-home')
                ->with('error', 'Could not store the skin file. Please try again.');
        }

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

        if ($skincount >= config('skins.max_upload')) {
            return redirect()
                ->route('skins-my')
                ->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        if (! SkinStorage::existsPlayer($gjid)) {
            return redirect()
                ->route('skin-home')
                ->with('error', 'Skin was not found!');
        }

        $skin = Skin::create([
            'owner_id' => $gjid,
            'user_id' => auth()->id(),
            'name' => 'Import: '.$gjid,
        ]);

        try {
            SkinStorage::copyPlayerToLibrary($gjid, $skin->uuid);
        } catch (UnableToReadFile|Exception|RuntimeException) {
            $skin->forceDelete();

            return redirect()
                ->route('skins-my')
                ->with('error', 'Could not duplicate skin.');
        }

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was duplicated!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $gjid = Auth::user()->gamejolt->id;

        if (! SkinStorage::existsPlayer($gjid)) {
            return redirect()
                ->route('skin-home')
                ->with('error', 'Skin was not found!');
        }

        SkinStorage::deletePlayer($gjid);

        return redirect()
            ->route('skins-my')
            ->with('success', 'Skin was successfully deleted!');
    }

    public function destroyAsAdmin(Request $request, int|string $id): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);

        if (! SkinStorage::existsPlayer($id)) {
            return redirect()
                ->route('player-skins')
                ->with('error', 'Skin was not found!');
        }

        activity()
            ->causedBy(Auth::user()->gamejolt)
            ->withProperties([
                'filename' => SkinStorage::playerPath($id),
                'gjid' => $id,
                'reason' => $request->string('reason')->toString(),
            ])
            ->log('deleted');

        SkinStorage::deletePlayer($id);

        return redirect()
            ->route('player-skins')
            ->with('success', 'Skin was successfully deleted!');
    }
}
