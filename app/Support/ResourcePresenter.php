<?php

namespace App\Support;

use App\Helpers\NumberHelper;
use App\Models\Resource;
use App\Models\ResourceUpdate;
use App\Models\User;
use Illuminate\Support\Str;

class ResourcePresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function card(Resource $resource): array
    {
        $latestUpdate = $resource->updates->first();
        $category = $resource->categories->first();

        return [
            'uuid' => $resource->uuid,
            'name' => $resource->name,
            'brief' => $resource->brief,
            'version' => $latestUpdate?->title ?? __('Unreleased'),
            'category' => $category?->name ?? __('Uncategorized'),
            'rating' => [
                'average' => $resource->hasReview() ? $resource->averageRating(1) : 0,
                'stars' => (int) ($resource->averageRating(0) ?? 0),
                'count' => $resource->numberOfReviews(),
            ],
            'likes' => $resource->likers_count ?? $resource->likers()->count(),
            'downloads' => (int) $resource->downloads,
            'updated_at' => $resource->updated_at?->diffForHumans(),
            'created_at' => $resource->created_at?->diffForHumans(),
            'author' => [
                'username' => $resource->user->username,
                'name' => $resource->user->name,
                'profile_photo_url' => $resource->user->profile_photo_url ?? asset('img/TreeLogoSmall.png'),
            ],
            'url' => route('resource.uuid', $resource->uuid),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function show(Resource $resource, ?User $viewer = null): array
    {
        $category = $resource->categories->first();
        $latestUpdate = $resource->updates->first();

        return [
            'uuid' => $resource->uuid,
            'name' => $resource->name,
            'brief' => $resource->brief,
            'description_html' => (string) Str::of($resource->description)->markdown(),
            'version' => $latestUpdate?->title ?? __('Unreleased'),
            'category' => $category
                ? [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'url' => route('resource.category', $category->slug),
                ]
                : null,
            'rating' => [
                'average' => $resource->hasReview() ? $resource->averageRating(1) : 0,
                'stars' => (int) ($resource->averageRating(0) ?? 0),
                'count' => $resource->numberOfReviews(),
            ],
            'likes' => [
                'count' => $resource->likers()->count(),
                'liked' => $viewer ? $resource->isLikedBy($viewer) : false,
            ],
            'downloads' => (int) $resource->downloads,
            'views' => (string) NumberHelper::nearestK(views($resource)->count()),
            'created_at' => $resource->created_at?->diffForHumans(),
            'updated_at' => $resource->updated_at?->diffForHumans(),
            'author' => [
                'username' => $resource->user->username,
                'profile_photo_url' => $resource->user->profile_photo_url ?? asset('img/TreeLogoSmall.png'),
                'url' => route('member.show', $resource->user->username),
            ],
            'updates' => $resource->updates
                ->map(fn (ResourceUpdate $update): array => self::update($update, $resource->uuid))
                ->values()
                ->all(),
            'reviews' => $resource->reviews->map(fn ($review): array => [
                'id' => $review->id,
                'body' => $review->review,
                'rating' => (int) $review->rating,
                'created_at' => $review->created_at?->diffForHumans(),
                'author' => [
                    'username' => $review->author?->username,
                    'profile_photo_url' => $review->author?->profile_photo_url ?? asset('img/TreeLogoSmall.png'),
                    'url' => $review->author ? route('member.show', $review->author->username) : null,
                ],
            ])->values()->all(),
            'latest_update_id' => $latestUpdate?->id,
            'permissions' => [
                'can_update' => $viewer ? $viewer->can('update', $resource) : false,
                'can_delete' => $viewer ? $viewer->can('delete', $resource) : false,
                'can_post_update' => $viewer ? $viewer->can('postUpdate', $resource) : false,
                'can_rate' => $viewer ? $viewer->can('rate', $resource) : false,
                'can_like' => $viewer ? $viewer->can('like', $resource) : false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function form(Resource $resource): array
    {
        return [
            'uuid' => $resource->uuid,
            'name' => $resource->name,
            'brief' => $resource->brief,
            'description' => $resource->description,
            'category_id' => $resource->categories->first()?->id,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(ResourceUpdate $update, ?string $resourceUuid = null): array
    {
        $uuid = $resourceUuid ?? $update->resource->uuid;

        return [
            'id' => $update->id,
            'title' => $update->title,
            'description_excerpt' => strip_tags((string) Str::of(Str::limit($update->description, 80))->markdown()),
            'description_html' => (string) Str::of($update->description)->markdown(),
            'game_version' => $update->game_version?->version,
            'downloads' => (int) $update->downloads,
            'created_at' => $update->created_at?->diffForHumans(),
            'download_url' => route('resource.updates.download', [
                'uuid' => $uuid,
                'update' => $update->id,
            ]),
        ];
    }
}
