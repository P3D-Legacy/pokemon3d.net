<?php

namespace App\Http\Controllers\Skin;

use App\Http\Controllers\Controller;
use App\Support\SkinPresenter;
use App\Support\SkinStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class SkinHomeController extends Controller
{
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $gamejolt = $user->gamejolt;
        $skins = $gamejolt->skins()->with('user')->withCount('likers')->latest()->get();

        $user->attachLikeStatus($skins);

        $hasCurrentSkin = SkinStorage::existsPlayer($gamejolt->id);
        $skinCount = $skins->count();
        $maxUpload = (int) config('skins.max_upload');

        $deleteActivity = Activity::query()
            ->where('description', 'deleted')
            ->where(function ($query) use ($gamejolt): void {
                $query->where('properties', 'LIKE', '%'.$gamejolt->id.'.png%')
                    ->orWhere('properties', 'LIKE', '%gjid":'.$gamejolt->id.',"reason"%');
            })
            ->orderByDesc('id')
            ->take(10)
            ->get()
            ->map(fn (Activity $log): array => [
                'created_at' => $log->created_at
                    ?->setTimezone($user->timezone ?? config('app.timezone'))
                    ?->toDateTimeString(),
                'reason' => $log->properties['reason'] ?? null,
            ])
            ->values()
            ->all();

        return Inertia::render('skins/home', [
            'skins' => $skins
                ->map(fn ($skin): array => SkinPresenter::card($skin, $user))
                ->values()
                ->all(),
            'currentSkin' => [
                'exists' => $hasCurrentSkin,
                'image_url' => $hasCurrentSkin
                    ? SkinStorage::urlPlayer($gamejolt->id)
                    : null,
            ],
            'slots' => [
                'used' => $skinCount,
                'max' => $maxUpload,
            ],
            'canImport' => ! $hasCurrentSkin && $skinCount < $maxUpload,
            'importUrl' => route('import', $gamejolt->id),
            'templateUrl' => asset('img/template.png'),
            'deleteActivity' => $deleteActivity,
            'width' => (int) config('skins.width'),
            'height' => (int) config('skins.height'),
        ]);
    }
}
