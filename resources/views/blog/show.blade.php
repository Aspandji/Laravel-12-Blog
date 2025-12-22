@extends('layouts.app')

@section('title', ($post->meta_title ?? $post->title) . ' - 69Dev')

@section('meta_description', $post->meta_description)
@section('meta_keywords', $post->meta_keywords ?: 'teknologi, ' . $post->category->name)

@section('og_type', 'article')
@section('og_title', $post->meta_title ?? $post->title)
@section('og_description', $post->meta_description)
@section('og_image', $post->featured_image_url)

@section('twitter_title', $post->meta_title ?? $post->title)
@section('twitter_description', $post->meta_description)
@section('twitter_image', $post->featured_image_url)

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

    @php
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => strip_tags($post->meta_description ?? ''),
            'image' => $post->featured_image_url,
            'datePublished' => optional($post->published_at)->toIso8601String(),
            'dateModified' => optional($post->updated_at)->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => '69Dev',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => '69Dev',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('blog.show', $post->slug),
            ],
        ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    @php
        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('blog.index'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $post->title,
                    'item' => route('blog.show', $post->slug),
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection




@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('blog.index') }}" class="hover:text-purple-600 transition">Home</a></li>
                <li><span>/</span></li>
                <li><a href="" class="hover:text-purple-600 transition">{{ $post->category->name }}</a></li>
                <li><span>/</span></li>
                <li class="text-gray-900">{{ Str::limit($post->title, 50) }}</li>
            </ol>
        </nav>

        <div class="flex flex-wrap -mx-4">
            <!-- Main Content -->
            <div class="w-full lg:w-2/3 px-4">
                <article class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-purple-500" itemscope
                    itemtype="https://schema.org/Article">
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-96 object-cover"
                        itemprop="image">

                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <a href=""
                                class="inline-block bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 text-sm font-semibold px-4 py-1 rounded-full hover:from-purple-200 hover:to-violet-200 transition">
                                {{ $post->category->name }}
                            </a>
                            <span class="text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <time datetime="{{ $post->published_at->toIso8601String() }}" itemprop="datePublished">
                                    {{ $post->published_at->format('F d, Y') }}
                                </time>
                            </span>
                        </div>

                        <h1 class="text-4xl font-bold mb-6 text-gray-900" itemprop="headline">{{ $post->title }}</h1>

                        @if ($post->excerpt)
                            <p class="text-xl text-gray-700 mb-8 italic border-l-4 border-purple-500 pl-4 bg-purple-50 py-4 rounded-r"
                                itemprop="description">
                                {{ $post->excerpt }}
                            </p>
                        @endif

                        <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-a:text-purple-600 prose-a:no-underline hover:prose-a:text-purple-800"
                            itemprop="articleBody">
                            {!! $post->content !!}
                        </div>

                        <meta itemprop="author" content="69Dev">
                        <meta itemprop="dateModified" content="{{ $post->updated_at->toIso8601String() }}">
                    </div>
                </article>

                <!-- Related Posts -->
                @if ($relatedPosts->count() > 0)
                    <div class="mt-12">
                        <h3 class="text-2xl font-bold mb-6 text-gray-900 flex items-center">
                            <span class="bg-gradient-to-r from-purple-600 to-violet-600 w-1 h-8 mr-3 rounded-full"></span>
                            Related Articles
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($relatedPosts as $related)
                                <article
                                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group border-t-2 border-purple-400">
                                    <div class="overflow-hidden">
                                        <img src="{{ $related->featured_image != null ? asset('storage/' . $related->featured_image) : asset('images/default-post.jpg') }}"
                                            alt="{{ $related->title }}"
                                            class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-bold mb-2 line-clamp-2">
                                            <a href="{{ route('blog.show', $related->slug) }}"
                                                class="hover:text-purple-600 transition">
                                                {{ $related->title }}
                                            </a>
                                        </h4>
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $related->published_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-8">
                    <a href="{{ route('blog.index') }}"
                        class="inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold group">
                        <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to all posts
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-1/3 px-4 mt-8 lg:mt-0">
                <!-- Recent Posts Widget -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-lg shadow-md p-6 sticky top-20 border border-purple-100">
                    <h3 class="text-xl font-bold mb-4 flex items-center text-gray-900">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
@endsection
