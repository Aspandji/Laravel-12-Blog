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
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    <meta property="og:site_name" content="69Dev">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('twitter_description', '69Dev - Portal teknologi terkini')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/logo.png'))">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">


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

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- JSON-LD Structured Data -->
    @yield('structured_data')

</head>

<body class="bg-gray-50">
    <nav x-data="{ openSearch: false }"
        class="relative bg-gradient-to-r from-purple-900 via-purple-800 to-violet-900
           shadow-lg sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">

                <!-- LOGO -->
                <a href="{{ route('blog.index') }}" class="flex items-center gap-2">

                    <!-- Mobile Logo -->
                    <img src="{{ asset('images/logo.png') }}" alt="69Dev"
                        class="h-20 w-auto sm:hidden object-contain">

                    <!-- Desktop Logo -->
                    <img src="{{ asset('images/logo.png') }}" alt="69Dev"
                        class="hidden sm:block h-32 w-auto object-contain">

                </a>

                <!-- DESKTOP SEARCH -->
                <div class="hidden sm:flex flex-1 max-w-lg mx-6">
                    <form action="{{ route('blog.search') }}" method="GET" class="relative w-full">

                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <!-- SVG SEARCH -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>

                        <input type="text" name="q" placeholder="Search articles..."
                            class="w-full px-4 py-2 pl-10 text-sm rounded-full
                               bg-white/90 text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-purple-400">
                    </form>
                </div>

                <!-- MOBILE SEARCH BUTTON -->
                <button @click="openSearch = !openSearch"
                    class="sm:hidden p-2 rounded-full bg-white/10 text-white
                       focus:outline-none focus:ring-2 focus:ring-white/30">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- MOBILE SEARCH DROPDOWN -->
        <div x-show="openSearch" x-transition x-cloak @click.outside="openSearch = false"
            class="sm:hidden absolute inset-x-0 top-full
               bg-purple-900/95 backdrop-blur
               px-4 py-3 shadow-lg">

            <form action="{{ route('blog.search') }}" method="GET" class="relative">

                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>

                <input type="text" name="q" placeholder="Search articles..."
                    class="w-full px-4 py-2 pl-10 text-sm
                       rounded-full bg-white text-gray-800
                       focus:outline-none focus:ring-2 focus:ring-purple-400">
            </form>
        </div>

    </nav>

    <main class="py-10">
        @yield('content')
    </main>

    <footer class="bg-gradient-to-r from-gray-900 via-purple-900 to-violet-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center">
                <a href="{{ route('blog.index') }}" class="flex justify-center items-center w-full">

                    <!-- Desktop Mobile -->
                    <img src="{{ asset('images/logo.png') }}" alt="69Dev"
                        class="h-24 w-auto sm:hidden object-contain">

                    <!-- Desktop Logo -->
                    <img src="{{ asset('images/logo.png') }}" alt="69Dev"
                        class="hidden sm:block h-32 w-auto object-contain">
                </a>

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

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>

</html>
