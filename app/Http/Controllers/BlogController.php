<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $posts = Post::where('published_at', '<=', now())
            ->where('active', true)
            ->orderBy('sticky', 'desc')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('blog.index', ['posts' => $posts]);
    }

    /**
     * Display the specified resource.
     *
     * @param $param
     * @return Application|Factory|View
     */
    public function show($param): Application|Factory|View
    {
        $post = Post::where('uuid', $param)
            ->where('active', true)
            ->orWhere('slug', $param)
            ->where('active', true)
            ->firstOrFail();
        abort_if(! $post, 404);
        views($post)
            ->cooldown(60)
            ->record();

        return view('blog.show', ['post' => $post]);
    }
}
