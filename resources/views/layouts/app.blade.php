<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', '69Dev - Portal Teknologi & Development')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', '69Dev - Portal berita teknologi, programming, dan inovasi terkini. Temukan artikel, tutorial, dan tips seputar dunia teknologi.')">
    <meta name="keywords" content="@yield('meta_keywords', 'teknologi, programming, web development, laravel, php, javascript')">
    <meta name="author" content="69Dev">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', '69Dev - Portal teknologi terkini')">
    <meta property="og:image" content="@yield('og_image', asset('images/default-og-image.jpg'))">
    <meta property="og:site_name" content="69Dev">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('twitter_description', '69Dev - Portal teknologi terkini')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/default-og-image.jpg'))">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Pagination SEO (jika ada pagination) -->
    @if (request()->has('page'))
        @if (request('page') > 1)
            <link rel="prev" href="{{ url()->current() }}?page={{ request('page') - 1 }}">
        @endif

        @if (isset($posts) && $posts->hasMorePages())
            <link rel="next" href="{{ url()->current() }}?page={{ request('page') + 1 }}">
        @endif
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- JSON-LD Structured Data -->
    @yield('structured_data')

</head>

<body class="bg-gray-50">
    <nav class="bg-gradient-to-r from-purple-900 via-purple-800 to-violet-900 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('blog.index') }}"
                        class="text-2xl font-bold text-white hover:text-purple-200 transition flex items-center gap-2">
                        <h3 class="text-3xl font-bold mb-2">
                            <span class="text-4xl text-purple-400">69</span>
                            <span>Dev</span>
                        </h3>
                    </a>
                </div>

                <!-- Search Form -->
                <div class="flex-1 max-w-lg mx-8">
                    <form action="{{ route('blog.search') }}" method="GET" class="relative">
                        <input type="text" name="q" minlength="3" value="{{ request('q') }}"
                            placeholder="Search articles..."
                            class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-white/90 border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white transition backdrop-blur-sm">
                        <button type="submit"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- <!-- Mobile Search Toggle -->
                <button id="search-toggle" class="lg:hidden text-white hover:text-purple-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button> --}}
            </div>

            <!-- Mobile Search Form -->
            {{-- <div id="mobile-search" class="hidden pb-4 lg:hidden">
                <form action="{{ route('blog.search') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles..."
                        class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-white/90 border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white transition">
                    <button type="submit"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div> --}}
        </div>
    </nav>

    <main class="py-10">
        @yield('content')
    </main>

    <footer class="bg-gradient-to-r from-gray-900 via-purple-900 to-violet-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center">
                <h3 class="text-3xl font-bold mb-2">
                    <span class="text-4xl text-purple-400">69</span>
                    <span>Dev</span>
                </h3>
                <p class="text-purple-200 mb-4">Your Lucky Tech Portal</p>
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} 69Dev. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- <script>
        // Mobile search toggle
        document.getElementById('search-toggle')?.addEventListener('click', function() {
            const mobileSearch = document.getElementById('mobile-search');
            mobileSearch.classList.toggle('hidden');
        });
    </script> --}}

</body>

</html>
