<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;


class PostController extends Controller
{
    public function index(): View
    {
        // শুধু published posts, সাথে author এর নাম (eager loading)
        $posts = Post::published() // Laravel automatically removes scope prefix -- scopePublished() থেকে published() method call করা হচ্ছে
            ->with('author') // N+1 problem avoid করতে eager load
            ->latest('published_at')
            ->paginate(10);

        // dd($posts->toArray()); // Debugging: posts data দেখার জন্য

        return View('posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        Gate::authorize('view', $post);

        // Author information সাথে নিয়ে আসো
        $post->load('author'); // relation theke author data eager load করা হচ্ছে

        return view('posts.show', compact('post'));
    }
    /**
     * User এর নিজের সব posts দেখায় (My Posts)
     */
    public function myPosts(Request $request): View
    {
        $posts = $request->user()
            ->posts()
            ->latest()
            ->paginate(10);

        return view('posts.my-posts', compact('posts'));
    }
    /**
     * নতুন post create form দেখায়
     * Policy: create - যেকোনো logged-in user
     */
    public function create(): View
    {
        // Authorization check using Gate
        Gate::authorize('create', Post::class);

        return view('posts.create');
    }
    /**
     * নতুন post database এ save করে
     *
     * Validation Rules:
     * - title: required, max 255 chars
     * - body: required
     *
     * নতুন post সবসময় 'draft' status এ create হয়
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        // Validation - Laravel এর built-in validation
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        // নতুন post create - user relationship এর মাধ্যমে
        // এতে automatically user_id set হয়ে যায়
        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => 'draft', // নতুন post সবসময় draft
        ]);

        return Redirect()
            ->route('posts.show', $post)
            ->with('success', 'পোস্ট সফলভাবে তৈরি হয়েছে!');
    }
}
