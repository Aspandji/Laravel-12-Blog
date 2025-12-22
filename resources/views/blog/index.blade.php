@extends('layouts.app')

@section('title', 'Home - 69Dev')

@section('structured_data')

    <!-- Add Schema.org Markup -->
    @php
        $collectionSchema =
            isset($posts) && $posts->hasPages()
                ? [
                    '@context' => 'https://schema.org',
                    '@type' => 'CollectionPage',
                    'url' => request()->fullUrlWithoutQuery('page'),
                    'name' => 'Blog Posts - Page ' . $posts->currentPage(),
                    'isPartOf' => [
                        '@type' => 'WebSite',
                        'name' => '69Dev',
                        'url' => url('/'),
                    ],
                ]
                : null;
    @endphp
    <script type="application/ld+json">
        {!! json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px6 lg:px-8">
        {{-- Hero Section --}}
        <div class="mb-12 text-center">
            <h1
                class="text-5xl font-bold bg-gradient-to-r from-purple-600 via-violet-600 to-purple-600 bg-clip-text text-transparent mb-4">
                Welcome to 69Dev
            </h1>
            <p class="text-xl text-gray-600">Explore the latest in technology, programming, and innovation</p>
        </div>

        <div class="flex flex-wrap -mx-4">
            <!-- Main Content -->
            <div class="w-full lg:w-2/3 px-4">
                <h2 class="text-3xl font-bold mb-6 text-gray-900">News Articles</h2>

                <div class="space-y-6">
                    @foreach ($posts as $post)
                        <article
                            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col md:flex-row group border-l-4 border-purple-500">
                            <div class="md:w-2/5 overflow-hidden">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                                    class="w-full h-64 md:h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <div class="p-6 {{ $post->featured_image_url ? 'md:w-3/5' : 'w-full' }}">
                                <div class="flex items-center gap-3 mb-3">
                                    {{-- <a href="{{ route('blog.category', $post->category->slug) }}"  --}}
                                    <a href=""
                                        class="inline-block bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full hover:from-purple-200 hover:to-violet-200 transition">
                                        {{ $post->category->name }}
                                    </a>
                                    <span class="text-sm text-gray-500">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </span>
                                </div>

                                <h3 class="text-2xl font-bold mb-3">
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                        class="hover:text-purple-600 transition">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                @if ($post->excerpt)
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                @endif

                                <a href="{{ route('blog.show', $post->slug) }}"
                                    class="inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold group">
                                    Read more
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links('vendor.pagination.numbered-purple') }}
                </div>
            </div>

            <div class="w-full lg:w-1/3 px-4 mt-8 lg:mt-0">
                <!-- Sticky Sidebar Wrapper -->
                <div class="sticky top-20 space-y-6">

                    <!-- Categories Widget -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500">
                        <h3 class="text-xl font-bold mb-4 flex items-center text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Categories
                        </h3>

                        <ul class="space-y-3">
                            @foreach ($categories as $category)
                                <li>
                                    <a href=""
                                        class="flex justify-between items-center p-2 rounded hover:bg-purple-50 transition group">
                                        <span class="group-hover:text-purple-600 font-medium">{{ $category->name }}</span>
                                        <span
                                            class="bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-1 rounded-full group-hover:bg-purple-200">
                                            {{ $category->posts_count }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Recent Posts Widget -->
                    <div
                        class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-lg shadow-md p-6 border border-purple-100">
                        <h3 class="text-xl font-bold mb-4 flex items-center text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Recent Posts
                        </h3>

                        <ul class="space-y-4">
                            @foreach ($recentPosts as $recentPost)
                                <li class="border-b border-purple-100 pb-4 last:border-b-0 last:pb-0">
                                    <a href="{{ route('blog.show', $recentPost->slug) }}" class="group">
                                        <h4
                                            class="font-semibold text-sm group-hover:text-purple-600 transition line-clamp-2 mb-1">
                                            {{ $recentPost->title }}
                                        </h4>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $recentPost->published_at->format('M d, Y') }}
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
