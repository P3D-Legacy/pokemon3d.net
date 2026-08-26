<?php

namespace App\Support;

use App\Helpers\NumberHelper;
use App\Models\Post;
use Illuminate\Support\Str;

class PostPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function card(Post $post): array
    {
        return [
            'uuid' => $post->uuid,
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'sticky' => (bool) $post->sticky,
            'published_at' => optional($post->published_at)->toIso8601String(),
            'published_for_humans' => now()->subYear() > $post->published_at
                ? $post->published_at->isoFormat('LL')
                : $post->published_at->diffForHumans(),
            'reading_time' => read_time($post->body),
            'comment_count' => (string) NumberHelper::nearestK($post->commentCount()),
            'view_count' => (string) NumberHelper::nearestK(views($post)->count()),
            'tag' => $post->tags->first()?->name,
            'author' => [
                'username' => $post->user->username,
                'profile_photo_url' => $post->user->profile_photo_url,
            ],
            'url' => route('blog.show', $post->uuid),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function detail(Post $post): array
    {
        return [
            'uuid' => $post->uuid,
            'title' => $post->title,
            'sticky' => (bool) $post->sticky,
            'body_html' => (string) Str::of($post->body)->markdown(),
            'published_at' => $post->published_at->isoFormat('LLLL'),
            'updated_at' => $post->updated_at?->isoFormat('LLLL'),
            'likes' => (string) NumberHelper::nearestK($post->likers->count()),
            'views' => (string) NumberHelper::nearestK(views($post)->count()),
            'reading_time' => read_time($post->body),
            'comments' => (string) NumberHelper::nearestK($post->commentCount()),
            'tags' => $post->tags->pluck('name')->all(),
            'is_outdated' => now()->subYear() > $post->updated_at,
            'author' => [
                'username' => $post->user->username,
                'profile_photo_url' => $post->user->profile_photo_url,
                'url' => route('member.show', $post->user->username),
            ],
        ];
    }
}
