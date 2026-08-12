<!DOCTYPE html>
<html lang="en">

<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title')
    </title>

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

    <!-- Tailwind: one prebuilt stylesheet. (Was previously TWO frameworks at
         once — a static v2.2.19 build AND the Play CDN, which compiles Tailwind
         in the browser on every page load.) -->
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ @filemtime(public_path('css/tailwind.css')) ?: 1 }}">

    <!-- Font Awesome (was loaded twice: 6.0.0-beta3 + 6.4.0) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google fonts: one request, only the weights actually used
         (was two overlapping requests totalling ~36 variants) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/vendor_dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor_chat.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor_navbar.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Shared brand styles (btn-brand-gradient, card-hover-glow, skeleton-shimmer, section-kicker, brand-divider) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ @filemtime(public_path('css/style.css')) ?: 1 }}">

    <style>
        .PFDI {
            font-family: "Playfair Display", serif;
            font-weight: 900;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: italic;
        }
    </style>

    @yield('style')

    <script src="{{ asset('js/page-loader.js') }}?v={{ @filemtime(public_path('js/page-loader.js')) ?: 1 }}"></script>
</head>
<body class="bg-gray-50 font-sans">
    @yield('body')

    <x-customer.global-nav />

    @yield('script')
</body>
</html>