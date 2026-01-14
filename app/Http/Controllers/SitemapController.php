<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Cache::remember('sitemap', 3600, function () {
            $posts = Post::where('is_published', true)
                ->latest('updated_at')
                ->get();

            $categories = Category::latest('updated_at')->get();

            return view('sitemap.index', [
                'posts' => $posts,
                'categories' => $categories,
            ])->render();
        });

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
}
