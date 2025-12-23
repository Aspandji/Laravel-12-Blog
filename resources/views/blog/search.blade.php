@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '" - 69Dev')

@php
    $collectionSchema =
        strlen(request('q')) >= 3
            ? [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'url' => request()->fullUrl(),
                'name' => 'Hasil Pencarian: ' . request('q'),
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => '69Dev',
                    'url' => url('/'),
                ],
            ]
            : null;
@endphp

@section('structured_data')
    @if (!empty($collectionSchema))
        <script type="application/ld+json">
            {!! json_encode(
                $collectionSchema,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ) !!}
        </script>
    @endif
@endsection


@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap -mx-4">
            <!-- Main Content -->
            <div class="w-full lg:w-2/3 px-4">
                <!-- Search Header -->
                @if (strlen($query) < 3)
                    <p class="text-red-500 text-lg">
                        Please enter at least <strong>3 characters</strong> to search.
                    </p>
                @else
                    <p class="text-xl text-gray-600">
                        Found
                        <span class="font-semibold text-purple-600">{{ $posts->total() }}</span>
                        {{ Str::plural('result', $posts->total()) }}
                        for "<span class="font-semibold">{{ $query }}</span>"
                    </p>
                @endif


                @if (strlen($query) >= 3 && $posts->count() > 0)
                    <div class="space-y-6">
                        @foreach ($posts as $post)
                            <article
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col md:flex-row group border-l-4 border-purple-500">
                                @if ($post->featured_image)
                                    <div class="md:w-2/5 overflow-hidden">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                            class="w-full h-64 md:h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                @endif

                                <div class="p-6 {{ $post->featured_image ? 'md:w-3/5' : 'w-full' }}">
                                    <div class="flex items-center gap-3 mb-3">
                                        <a href="{{ route('blog.category', $post->category->slug) }}"
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
                                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if ($posts->hasPages())
                        <div class="mt-8">
                            {{ $posts->links('vendor.pagination.numbered-purple') }}
                        </div>
                    @endif
                @endif

                @if (strlen($query) >= 3 && $posts->count() === 0)
                    <!-- No Results -->
                    <div class="bg-white rounded-lg shadow-md p-12 text-center border-t-4 border-purple-500">
                        <svg class="w-24 h-24 mx-auto text-purple-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">No Results Found</h3>
                        <p class="text-gray-500 mb-6">We couldn't find any articles matching "{{ $query }}"</p>
                        <a href="{{ route('blog.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Back to Home
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-1/3 px-4 mt-8 lg:mt-0">
                <!-- Search Tips Widget -->

                <div class="sticky top-20 space-y-6">

                    <div class="bg-gradient-to-br from-purple-50 to-violet-50 border border-purple-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-purple-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Search Tips
                        </h3>
                        <ul class="text-sm text-purple-800 space-y-2">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Gunakan kata kunci spesifik untuk hasil yang lebih baik</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Cobalah berbagai variasi istilah pencarian Anda</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Jelajahi kategori untuk topik terkait</span>
                            </li>
                        </ul>
                    </div>

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
                                    <a href="{{ route('blog.category', $category->slug) }}"
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
