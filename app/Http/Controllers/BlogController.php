<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Get Data all Post
        // $posts = Post::with('category')
        //     ->where('is_published', true)
        //     ->latest('published_at')
        //     ->paginate(6);

        // Menggunakan Query Scope
        $posts = Post::withCategory()->latestPublished()->paginate(7);

        // Get category + jumlah Post
        $categories = Category::withCount('posts')->get();

        // Get Recent Post
        // $recentPosts = Post::with('category')
        //     ->where('is_published', true)
        //     ->latest('published_at')
        //     ->take(5)
        //     ->get();

        $recentPosts = Post::withCategory()->latestPublished()->take(5)->get();

        return view('blog.index', compact('posts', 'categories', 'recentPosts'));
    }

    public function show($slug)
    {
        // Get detail post
        $post = Post::withCategory()
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Recent Post
        $recentPosts = Post::withCategory()
            ->latestPublished()
            ->where('id', '!=', $post->id)
            ->take(5)
            ->get();

        // Related Post
        $relatedPosts = Post::withCategory()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latestPublished()
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'recentPosts', 'relatedPosts'));
    }
}
