<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * @group Post
 *
 * APIs for posts.
 */
class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:post.create'], only: ['store']),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @bodyParam title string required The title of the post. Example: Hello World
     * @bodyParam body string required The content of the post. Example: This is a post.
     * @bodyParam active boolean required Whether the post is active or not. Example: true
     * @bodyParam sticky boolean required Whether the post is sticky or not. Example: false
     * @bodyParam user_id int required The ID of the user. Example: 1
     * @bodyParam published_at string optional The date the post was published. Example: 2021-01-01
     *
     * @apiResourceModel App\Models\Post
     *
     * @apiResource App\Http\Resources\API\v1\PostResource
     *
     **/
    public function store(Request $request): PostResource
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'active' => 'required|boolean',
            'sticky' => 'required|boolean',
            'published_at' => 'required|date',
            'user_id' => 'required|integer',
        ]);
        $post = Post::create($request->all());

        return new PostResource($post);
    }
}
