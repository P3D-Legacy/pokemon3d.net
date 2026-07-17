<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\PostPresenter;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $posts = Post::query()
            ->where('published_at', '<=', now())
            ->where('active', true)
            ->orderBy('sticky', 'desc')
            ->orderByDesc('published_at')
            ->with(['user', 'tags'])
            ->paginate(9)
            ->through(fn (Post $post): array => PostPresenter::card($post));

        return Inertia::render('blog/index', [
            'posts' => $posts,
            'copy' => [
                'title' => __('Official Blog'),
                'subtitle' => __('This is the official blog of the team and developers of the game.'),
                'nothingToShow' => __('There is nothing to show'),
                'published' => __('Published'),
                'readingTime' => __('Reading time'),
                'comments' => __('Comments'),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $param): Response
    {
        $post = Post::query()
            ->where('active', true)
            ->where(function ($query) use ($param): void {
                $query->where('uuid', $param)->orWhere('slug', $param);
            })
            ->with(['user', 'tags', 'likers'])
            ->firstOrFail();

        views($post)
            ->cooldown(60)
            ->record();

        return Inertia::render('blog/show', [
            'post' => PostPresenter::detail($post),
            'copy' => [
                'likes' => __('Likes'),
                'views' => __('Views'),
                'comments' => __('Comments'),
                'updated' => __('Updated'),
                'outdatedNote' => __('Note: This article was published over a year ago. Information within may have changed since then. While efforts are made to keep content current, please verify critical details before making decisions based on this information.'),
                'blog' => __('Blog'),
            ],
        ]);
    }
}
