<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use App\Support\SkinPresenter;
use App\Support\SkinStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
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
        $skins = Skin::query()
            ->with('user')
            ->withCount('likers')
            ->latest()
            ->paginate(24);

        if ($user) {
            $user->attachLikeStatus($skins);
        }

        return Inertia::render('skins/uploaded', [
            'skins' => $skins->through(fn (Skin $skin): array => SkinPresenter::card($skin, $user)),
        ]);
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);

        $skin = Skin::query()->where('uuid', $id)->first();
        abort_unless($skin, 404);

        activity()
            ->causedBy(Auth::user()->gamejolt)
            ->withProperties([
                'filename' => $skin->path(),
                'gjid' => $skin->owner_id,
                'reason' => $request->string('reason')->toString(),
            ])
            ->log('deleted');

        SkinStorage::deleteLibrary($skin->uuid);
        $skin->delete();

        return redirect()
            ->route('uploaded-skins')
            ->with('success', 'Skin was successfully deleted!');
    }
}
