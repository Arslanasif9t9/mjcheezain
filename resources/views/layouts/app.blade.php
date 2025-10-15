<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Parts & Cosmetics</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="MJCheezain.com – Discover unique and quality products with excellent customer support. Visit our online store today.">
    <meta name="keywords" content="MJCheezain, mjcheezain.com, online store, unique items">
    <meta name="author" content="MJCheezain">
    <meta property="og:title" content="MJCheezain – Unique Items">
    <meta property="og:description" content="Discover great products only at MJCheezain.com.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('img/logo.jpg') }}">

    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- ADD this new one -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login&signup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/d-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .search-bar { border: 1px solid #ccc; border-radius: 4px; }
        .icon-button:hover { background-color: #444; }
        .input::placeholder { color: #999; }
        .cat-con { flex-wrap: wrap; justify-content: center; align-items: center; }
        .cat-con a img { border-radius: 50%; border: 1px solid black; height: 100px; width: 100px; background-color: rgb(229, 229, 229); }
        .cat-con a { width: 100px !important; }
        .cat-con a span { display: block; font-weight: bold; }
        .popular-brands { margin: 30px auto !important; }
        .pb-con a img { width: 100px; height: 100px; border-radius: 50%; }
        .header-front { background: url("{{ asset('img/front-header-bg.jpeg') }}"); background-position: center; background-size: cover; }
        .header-front .container { grid-template-columns: 1fr; }
        .header-front .container .text-left { justify-self: start; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Display Session Messages -->
    @if(session('error'))
        <x-alert type="error" message="{{ session('error') }}" />
    @endif

    @if(session('loginError'))
        <x-alert type="error" message="{{ session('loginError') }}" />
    @endif

    @if(session('success'))
        <x-alert type="success" message="{{ session('success') }}" />
    @endif

    <x-header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main>
        @yield('content')
        {{-- <script src="{{ asset('js/search_product.js') }}"></script> --}}
        <x-Banner />
    </main>

    <!-- Login/Signup Modal -->
    {{-- <x-auth-modal /> --}}

    <!-- Scripts -->
    <script src="{{ asset('js/login&signup.js') }}"></script>
    <script src="{{ asset('js/javascript.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function userType(type) {
            document.getElementById('userType').value = type;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            
            searchButton.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') performSearch();
            });

            function performSearch() {
                const searchTerm = searchInput.value.trim();
                const category = document.getElementById('categorySelect').value;
                
                // AJAX search implementation
                fetch(`/search?q=${encodeURIComponent(searchTerm)}&category=${encodeURIComponent(category)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Handle search results
                        console.log('Search results:', data);
                    });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>