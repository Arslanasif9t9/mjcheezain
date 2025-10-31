<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Parts & Perfume</title>
    
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
        .search-bar {
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .icon-button:hover {
            background-color: #444;
        }

        .input::placeholder {
            color: #999;
        }

        .cat-con {
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .cat-con a img {
            border-radius: 50%;
            border: 1px solid black;
            height: 100px;
            width: 100px;
            background-color: rgb(229, 229, 229);
        }

        .cat-con a {
            width: 100px !important;
        }

        .cat-con a span {
            display: block;
            font-weight: bold;
        }

        .popular-brands {
            margin: 30px auto !important;
        }

        .pb-con a img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .header-front {
            background: url("{{ asset('img/front-header-bg.jpeg') }}");
            background-position: center;
            background-size: cover;
        }

        .header-front .container {
            grid-template-columns: 1fr;
        }

        .header-front .container .text-left {
            justify-self: start;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Display Errors -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('loginError'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('loginError') }}</span>
            <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <header class="bg-black text-white shadow-lg header-front position-sticky top-0" style="z-index: 1003;">
        <div class="container mx-auto items-center justify-between px-4 md:px-20 py-0">
            <!-- Logo Section -->
            <div class="m-auto grid grid-cols-1 md:grid-cols-3 items-center">
                <img src="{{ asset('img/logo-mj7.png') }}" alt="Company Logo" class="h-16 w-28 justify-self-center md:justify-self-start opacity-0 hidden md:block">
                <img src="{{ asset('img/logo-ss.png') }}" alt="Company Logo" class="h-32 justify-self-center pr-0 md:mr-24 brightness-200">
                <p class="text-right text-sm hidden md:block">
                    <span><i class="fa fa-phone"></i> 03**-*******</span> 
                    &nbsp; &nbsp; &nbsp; &nbsp; 
                    <span><i class="fa fa-envelope"></i> aqi*********@gmail.com</span>
                </p>
            </div>
        </div>
        
        <div class="text-left py-2 bg-gray-800 md:px-16 w-full">
            <p class="text-sm font-bold px-3">
                <a href="{{ url('/brands/cosmetics') }}" style="color: whitesmoke; text-decoration: none;">
                    <i class="fas fa-tools"></i> COSMETICS
                </a>
            </p>
        </div>
    </header>

    <!-- Search Bar -->
    <div class="flex flex-col md:flex-row items-center justify-between space-x-4 px-2 md:mx-16 mt-8 rounded-[4px]">
        <div class="flex items-center w-full max-w-2xl">
            <div class="grid grid-cols-7 md:flex search-bar w-full max-w-2xl">
                <input type="text" id="searchInput" placeholder="Explore autoparts, Perfume, Fashionable...."
                    class="w-[66vw] col-span-4 px-4 py-2 outline-none input rounded-[4px]" />
                <select id="categorySelect" class="col-span-2 px-2 border-l border-gray-300 text-sm text-gray-700">
                    <option>All Categories</option>
                    <option>Auto Parts</option>
                    <option>Cars & Vehicles</option>
                    <option>Women's Fashion</option>
                    <option>Men's Accessories</option>
                    <option>Fitness Equipment</option>
                    <option>Perfumes & Fragrances</option>
                </select>
                <button id="searchButton" class="bg-gray-700 text-white px-4 flex items-center justify-center rounded-[4px]">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- User Authentication Section -->
        @auth
            <!-- User is logged in -->
            @php
                $user = DB::table('users')->where('user_id', session('user_id'))->first();
                if ($user->type == 'vendor') {
                    $profile = DB::table('vendor_basic_info')->where('user_id', $user->user_id)->first();
                    $imgPath = $profile->profile_picture ? "vendor/{$profile->profile_picture}" : "img/default_profile.webp";
                    $dashboardPage = url('/vendor/dashboard');
                } else {
                    $profile = DB::table('customer_profile')->where('user_id', $user->user_id)->first();
                    $imgPath = $profile->profile_image ? "customer/{$profile->profile_image}" : "img/default_profile.webp";
                    $dashboardPage = url('/customer/dashboard');
                }
            @endphp
            <div class="text-right relative self-end my-1">
                <a href="{{ $dashboardPage }}">
                    <img class="w-[50px] h-[50px] rounded-full" src="{{ asset($imgPath) }}" alt="Profile">
                </a>
                <span class="absolute bottom-[-5px] right-[0px] bg-black px-1 rounded-full text-white">
                    <i class="fas fa-bars"></i>
                </span>
            </div>
        @else
            <!-- User is not logged in -->
            <div class="text-right flex self-end bg-gray-400 text-white rounded-[5px] my-1">
                <div class="position-relative group">
                    <button class="text-white hover:text-blue-600 px-3 py-2 text-sm font-medium flex items-center">
                        <p onmouseover="this.style.color='blue'" onmouseout="this.style.color='white'">Sign Up &nbsp; <i class="fa fa-caret-down"></i></p>
                    </button>
                    <div class="absolute w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block" style="z-index: 100;">
                        <a href="#" class="login-btn text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600" onclick="userType('customer')">
                            Customer Sign Up
                        </a>
                        <a href="#" class="login-btn text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600" onclick="userType('vendor')">
                            Vendor Sign Up
                        </a>
                    </div>
                </div>
                <div class="text-gray-500"> | </div>
                <div class="position-relative group">
                    <button class="text-white hover:text-orange-600 px-3 py-2 text-sm font-medium flex items-center">
                        <p onmouseover="this.style.color='blue'" onmouseout="this.style.color='white'">Login &nbsp; <i class="fa fa-caret-down"></i></p>
                    </button>
                    <div class="absolute w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block" style="z-index: 100;">
                        <a href="#" class="login-btn text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Customer Login</a>
                        <a href="#" class="login-btn text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Vendor Login</a>
                    </div>
                </div>
            </div>
        @endauth
    </div>

    <hr class="my-2">

    <!-- Rest of your HTML content remains the same -->
    <!-- Search Results Section -->
    <section id="searchResults" class="bg-white p-4 m-auto mt-4 hidden">
        <h2 class="font-bold mb-4">Search Results</h2>
        <div class="grid grid-cols-5 gap-4" id="productsGrid"></div>
        <div class="flex justify-center mt-4">
            <button id="loadMore" class="bg-gray-700 text-white px-4 py-2 rounded hidden">Load More</button>
        </div>
    </section>

    <!-- Login/Signup Modal -->
    <div class="blur-bg-overlay"></div>
    <div class="form-popup" style="z-index: 1100">
        <span class="close-btn material-symbols-rounded">close</span>
        <div class="form-box login">
            <div class="form-details">
                <h2>Welcome Back</h2>
                <p>Please log in using your personal information to stay connected with us.</p>
            </div>
            <div class="form-content">
                <h2>LOGIN</h2>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="input-field">
                        <input type="text" name="id" required>
                        <label>Email or username</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <a href="#" class="forgot-pass-link">Forgot password?</a>
                    <button type="submit">Log In</button>
                </form>
                <div class="bottom-link">
                    Don't have an account?
                    <a href="#" id="signup-link">Signup</a>
                </div>
            </div>
        </div>
        <div class="form-box signup">
            <div class="form-details">
                <h2>Create Account</h2>
                <p>To become a part of our community, please sign up using your personal information.</p>
            </div>
            <div class="form-content">
                <h2>SIGNUP</h2>
                <form action="{{ route('signup') }}" method="POST">
                    @csrf
                    <input id="userType" type="hidden" name="type" value="">
                    <div class="input-field">
                        <input type="text" name="name" required>
                        <label>Full Name</label>
                    </div>
                    <div class="input-field">
                        <input type="email" name="email" required>
                        <label>Enter your email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" required>
                        <label>Create password</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password_confirmation" required>
                        <label>Confirm password</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="phone" required>
                        <label>Phone</label>
                    </div>
                    <div class="policy-text">
                        <input type="checkbox" id="policy" name="policy" required>
                        <label for="policy">
                            I agree the
                            <a href="{{ url('/html_pages/term&condition') }}" class="option">Terms & Conditions</a>
                        </label>
                    </div>
                    <button type="submit">Sign Up</button>
                </form>
                <div class="bottom-link">
                    Already have an account?
                    <a href="#" id="login-link">Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript files -->
    <script src="{{ asset('js/login&signup.js') }}"></script>
    <script src="{{ asset('js/javascript.js') }}"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function userType(type) {
            document.getElementById('userType').value = type;
        }

        // Search functionality
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
                
                // Implement your search logic here
                console.log('Searching for:', searchTerm, 'in category:', category);
            }
        });
    </script>
</body>
</html>