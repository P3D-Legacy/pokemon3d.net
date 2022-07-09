<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('published_at', '<=', now())
            ->where('active', true)
            ->orderBy('sticky', 'desc')
            ->orderByDesc('published_at')
            ->paginate(5);

        return view('blog.index', ['posts' => $posts]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($param)
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
