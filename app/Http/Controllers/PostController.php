<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        // শুধু published posts, সাথে author এর নাম (eager loading)
        $posts = Post::published()
            ->with('author') // N+1 problem avoid করতে eager load
            ->latest('published_at')
            ->paginate(10);

        return View('posts.index', compact('posts'));
    }
    public function show(Post $post): View
    {
        Gate::authorize('view', $post);

        // Author information সাথে নিয়ে আসো
        $post->load('author');

        return view('posts.show', compact('post'));
    }
}
