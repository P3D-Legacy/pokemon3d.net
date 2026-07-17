<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use App\Support\SkinPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class UploadedSkinController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:skin-player-destroy'),
        ];
    }

    public function index(Request $request): Response
    {
        $user = $request->user();
        $skins = Skin::query()->with('user')->withCount('likers')->latest()->get();

        if ($user) {
            $user->attachLikeStatus($skins);
        }

        return Inertia::render('skins/uploaded', [
            'skins' => $skins
                ->map(fn (Skin $skin): array => SkinPresenter::card($skin, $user))
                ->values()
                ->all(),
        ]);
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);

        $skin = Skin::where('uuid', $id)->first();
        abort_unless($skin, 404);

        if (! Storage::disk('skin')->exists($skin->path())) {
            return redirect()
                ->route('uploaded-skins')
                ->with('error', 'Skin was not found!');
        }

        activity()
            ->causedBy(Auth::user()->gamejolt)
            ->withProperties([
                'filename' => $skin->path(),
                'gjid' => $skin->owner_id,
                'reason' => $request->reason,
            ])
            ->log('deleted');

        $skin->delete();
        Storage::disk('skin')->delete($skin->path());

        return redirect()
            ->route('uploaded-skins')
            ->with('success', 'Skin was successfully deleted!');
    }
}
