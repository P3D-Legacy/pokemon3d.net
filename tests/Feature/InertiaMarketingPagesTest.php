<?php

use App\Models\Post;
use Inertia\Testing\AssertableInertia as Assert;

test('home page is rendered with inertia', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('home')
            ->has('posts')
            ->has('stats')
            ->has('screenshots')
            ->has('download')
            ->has('copy'));
});

test('legal page is rendered with inertia', function () {
    $this->get(route('legal'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('legal')
            ->where('title', 'Legal')
            ->where('category', 'Legal')
            ->has('updatedAt')
            ->has('readTime')
            ->missing('html'));
});

test('contact page is rendered with inertia', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('contact')
            ->where('title', 'Contact')
            ->where('category', 'Contact')
            ->has('updatedAt')
            ->has('readTime')
            ->missing('html'));
});

test('blog index is rendered with inertia', function () {
    Post::factory()->create([
        'active' => true,
        'published_at' => now()->subDay(),
    ]);

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('blog/index')
            ->has('posts.data')
            ->has('copy'));
});

test('blog show is rendered with inertia', function () {
    $post = Post::factory()->create([
        'active' => true,
        'published_at' => now()->subDay(),
        'body' => '# Hello world',
    ]);

    $this->get(route('blog.show', $post->uuid))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('blog/show')
            ->where('post.title', $post->title)
            ->has('copy'));
});
