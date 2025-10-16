@props(['profilePicture', 'fullName', 'profile_visibility', 'page'])
<!-- Sidebar Toggle Button -->
<button id="btn-side" onclick="navbarToggle()" 
    class="md:hidden fixed top-4 left-4 m-2 z-50 text-gray-700 hover:text-red-200 transition-colors duration-200 text-2xl">
    <i id="toggleIcon" class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<aside id="aside" 
    class="fixd top-0 left-0 h-[100vh] w-64 bg-gray-900 text-white p-4 transform -translate-x-full transition-transform duration-300 md:translate-x-0 z-40 shadow-lg">
    
    <div class="flex flex-col items-center">
        <img class="w-24 h-24 rounded-full object-cover" src="{{ $profilePicture }}" alt="Profile" />
        <h2 class="mt-4 font-semibold text-xl">{{ $fullName }}</h2>

        @if($profile_visibility)
            <span class='active-button mt-1 bg-green-500 px-2 rounded-full'>Active</span>
        @else
            <span class='active-button mt-1 bg-red-500 px-2 rounded-full'>Close</span>
        @endif

        <div class="text-yellow-500 mb-4 text-lg">★★★★★</div>
    </div>

    <nav class="space-y-4">
        <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded">
            <i class="fa fa-chart-bar"></i> Dashboard
        </a>
        <a href="{{ route('vendor.products') }}" class="flex items-center gap-2">
            <i class="fa fa-box"></i> Products
        </a>
        <a href="{{ route('vendor.orders') }}" class="flex items-center gap-2">
            <i class="fa fa-shopping-cart"></i> Orders
        </a>
        <a href="{{ route('vendor.withdraw') }}" class="flex items-center gap-2">
            <i class="fa fa-wallet"></i> Withdraw
        </a>
        <a href="{{ route('vendor.profile') }}" class="flex items-center gap-2">
            <i class="fa-solid fa-user"></i> Profile
        </a>
        <a href="/" id="logoutBtn" class="flex items-center gap-2 text-red-400 rounded-lg hover:bg-gray-800 hover:text-red-300 transition-colors">
            <i class="fas fa-sign-out-alt w-5 text-center"></i> Log out
        </a>
    </nav>
</aside>

<script>
    function navbarToggle() {
        button = document.getElementById('btn-side');
        button.classList.toggle('text-white');

        const aside = document.getElementById('aside');
        const icon = document.getElementById('toggleIcon');

        aside.classList.toggle('-translate-x-full');

        // Toggle icon between bars and close
        if (aside.classList.contains('-translate-x-full')) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        } else {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        }
    }

    function setActive(page) {
        // Remove active styles from all links
        document.querySelectorAll('nav a').forEach(link => {
            link.classList.remove('bg-red-500', 'text-white');
        });
        const pageMap = {
            'Dashboard': 'vendor/dashboard',
            'Products': 'vendor/products',
            'Orders': 'vendor/orders',
            'Withdraw': 'vendor/withdraw',
            'Profile': 'vendor/profile'
        };
        document.querySelectorAll('nav a').forEach(link => {
            if (link.href.includes(pageMap[page])) {
                // console.log(link)
                link.classList.add('bg-red-500', 'text-white', 'rounded');
            }
        });
    }


    let page = '{{$page}}';
    setActive(page);
    
</script>
