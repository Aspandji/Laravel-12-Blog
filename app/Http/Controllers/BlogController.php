<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Get Data Post
        $posts = Post::with('category')
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(6);

        // Get category + jumlah Post
        $categories = Category::withCount('posts')->get();

        // Get Recent Post
        $recentPosts = Post::with('category')
            ->where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'recentPosts'));
    }
}
