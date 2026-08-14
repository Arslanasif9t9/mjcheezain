<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <script src="{{ asset('js/page-loader.js') }}?v={{ @filemtime(public_path('js/page-loader.js')) ?: 1 }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#FF7DA0">
    <title>Auto Parts & Cosmetics</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="MJCheezain.com – Discover unique and quality products with excellent customer support. Visit our online store today.">
    <meta name="keywords" content="MJCheezain, mjcheezain.com, online store, unique items">
    <meta name="author" content="MJCheezain">
    <meta property="og:title" content="MJCheezain – Unique Items">
    <meta property="og:description" content="Discover great products only at MJCheezain.com.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('img/short_logo.jpeg') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('img/short_logo.jpeg') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tailwind: one prebuilt stylesheet (was the Play CDN, which compiled
         Tailwind in the browser on every page load). --}}
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ @filemtime(public_path('css/tailwind.css')) ?: 1 }}">

    <!-- Google Fonts: one request, only the weights actually used -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">

    <!-- Custom CSS (cache-busted by file modification time so live users
         always get the latest styles after a deploy) -->
    <link rel="stylesheet" href="{{ asset('css/d-mode.css') }}?v={{ @filemtime(public_path('css/d-mode.css')) ?: 1 }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ @filemtime(public_path('css/style.css')) ?: 1 }}">

    <style>
        html { -webkit-text-size-adjust: 100%; }
        /* overflow-x: clip (not hidden) — hidden creates a scroll container on
           body which silently breaks position:sticky on the header */
        html, body { overflow-x: clip; max-width: 100vw; }
        body { -webkit-tap-highlight-color: transparent; }
        img, svg { max-width: 100%; }
        .search-bar { border: 1px solid #ccc; border-radius: 4px; }
        .icon-button:hover { background-color: #444; }
        .input::placeholder { color: #999; }
        .cat-con { flex-wrap: wrap; justify-content: center; align-items: center; }
        .cat-con a img { border-radius: 50%; border: 1px solid black; height: 100px; width: 100px; background-color: rgb(229, 229, 229); }
        .cat-con a { width: 100px !important; }
        .cat-con a span { display: block; font-weight: bold; }
        .pb-con a img { width: 100px; height: 100px; border-radius: 50%; }
        .header-front { background: url("{{ asset('img/front-header-bg.jpeg') }}"); background-position: center; background-size: cover; }
        .header-front .container { grid-template-columns: 1fr; }
        .header-front .container .text-left { justify-self: start; }
        * {
            font-family: "Poppins", sans-serif;
        }
        .PFDI {
            font-family: "Playfair Display", serif;
            font-weight: 900;
            font-optical-sizing: auto;
            font-style: italic;
        }
        /* Hide scrollbar for the horizontally-scrolling mobile filter-chip row,
           while keeping it scrollable (this class was used but never defined). */
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body class="">
    <!-- Display Session Messages -->
    @if ($errors->any())
        <div class="alert alert-danger mb-0 alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div class="flex-grow-1">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <x-header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main id="main">
        @yield('content')
        {{-- <script src="{{ asset('js/search_product.js') }}"></script> --}}
        {{-- <x-Banner /> --}}
        <x-categories />
        <x-vendors />
        {{-- @include('products.biggest-savings') --}}
        {{-- Product rails come from the admin Categories page (the "HOME" switch),
             not a hardcoded list — turning HOME on for a category now actually
             makes its products appear here. A category with no approved products
             hides its own section. --}}
        @foreach (\App\Support\CategoryCatalog::forHomeStocked() as $homeCat)
            @include('products.category', [
                'category' => $homeCat->name,
                'id'       => \Illuminate\Support\Str::slug($homeCat->name) ?: 'cat-' . $homeCat->id,
                'related'  => false,
            ])
        @endforeach
        {{-- @include('products.category', ['category' => 'Car Tools & Maintenance', 'id' => 'car']) --}}
        {{-- @include('products.category', ['category' => 'Mens Accessories', 'id' => 'cr']) --}}
    </main>

    <x-footer />

    <!-- Floating Back-to-Top Button -->
    <button id="back-to-top" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>
    <script>
        (function() {
            const btn = document.getElementById('back-to-top');
            if (!btn) return;
            window.addEventListener('scroll', function() {
                if (window.scrollY > 500) {
                    btn.classList.add('is-visible');
                } else {
                    btn.classList.remove('is-visible');
                }
            });
        })();
    </script>

    <!-- Login/Signup Modal -->

    <!-- Scripts -->
    <script src="{{ asset('js/search.js') }}?v={{ @filemtime(public_path('js/search.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/javascript.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function userType(type, x) {
            if (x == 'sign') {
                document.getElementById('userTypeSign').value = type;
            }
            else if (x == 'log') {
                document.getElementById('userTypeLog').value = type;
            }
        }
    </script>
    <script src="{{ asset('js/product-card.js') }}?v={{ @filemtime(public_path('js/product-card.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/category_fetch_v2.js') }}?v={{ @filemtime(public_path('js/category_fetch_v2.js')) ?: 1 }}"></script>

    <x-customer.global-nav />

    @stack('scripts')
</body>
</html>