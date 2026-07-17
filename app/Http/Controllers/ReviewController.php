<?php

namespace App\Http\Controllers;

use App\Models\GameVersion;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    /**
     * Display game version reviews.
     */
    public function index(): Response
    {
        $reviews = Review::query()
            ->where('model_type', GameVersion::class)
            ->with(['author', 'model'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Review $review): array => [
                'id' => $review->id,
                'rating' => (int) $review->rating,
                'body' => $review->review,
                'version' => $review->model?->version,
                'created_for_humans' => $review->created_at?->diffForHumans(),
                'author' => [
                    'name' => $review->author?->name ?? $review->author?->username ?? 'Anonymous',
                    'username' => $review->author?->username,
                    'profile_photo_url' => $review->author?->profile_photo_url,
                ],
            ])
            ->values();

        return Inertia::render('review/index', [
            'reviews' => $reviews,
            'averageRating' => round((float) $reviews->avg('rating'), 1),
            'numberOfReviews' => $reviews->count(),
            'gameVersions' => GameVersion::query()
                ->orderByDesc('release_date')
                ->take(3)
                ->get(['id', 'version', 'release_date'])
                ->map(fn (GameVersion $version): array => [
                    'id' => $version->id,
                    'version' => $version->version,
                    'release_date' => optional($version->release_date)->toDateString(),
                ])
                ->values(),
            'canCreate' => auth()->check(),
        ]);
    }

    /**
     * Store a new game version review.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user(), 403);

        $validated = $request->validate([
            'gameversion' => ['required', 'exists:game_versions,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:10', 'max:255'],
        ]);

        $gameVersion = GameVersion::query()->findOrFail($validated['gameversion']);
        $gameVersion->review($validated['body'], $request->user(), $validated['rating']);

        return back();
    }
}
