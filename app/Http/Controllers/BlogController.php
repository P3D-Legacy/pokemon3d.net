<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('updated_at')->where('active', true)->paginate(5);
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
            ->orWhere('slug', $param)
            ->firstOrFail();
        abort_if(!$post->active, 404);
        views($post)->cooldown(60)->record();
        return view('blog.show', ['post' => $post]);
    }
}
