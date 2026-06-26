@props(['profilePicture', 'fullName', 'profile_visibility', 'page', 'user'])

<!-- Sidebar Toggle Button -->
<button id="btn-side" onclick="navbarToggle()" 
    class="md:hidden fixed top-4 left-4 m-2 z-50 text-gray-700 hover:text-red-200 transition-colors duration-200 text-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-md p-2 rounded-lg shadow-sm flex items-center justify-center w-10 h-10 border border-gray-200">
    <i id="toggleIcon" class="fas fa-bars"></i>
</button>

<!-- Backdrop Overlay for Mobile -->
<div id="sidebarBackdrop" onclick="navbarToggle()" 
     class="fixed inset-0 bg-black bg-opacity-60 transition-opacity duration-300 opacity-0 pointer-events-none z-30 md:hidden"></div>

<aside id="aside" 
    class="fixed md:sticky top-0 left-0 h-[100vh] w-64 bg-gray-900 text-white p-6 transform -translate-x-full transition-transform duration-300 md:translate-x-0 z-40 shadow-xl flex flex-col justify-between">
    
    <div class="flex flex-col">
        <!-- Brand/Header -->
        <div class="flex items-center gap-3 mb-8 border-b border-gray-800 pb-4">
            <span class="text-red-500 font-extrabold text-2xl tracking-wider">MJ</span>
            <span class="font-bold text-lg tracking-normal">Vendor Center</span>
        </div>

        <!-- Profile Info -->
        <div class="flex flex-col items-center mb-8 bg-gray-850 p-4 rounded-xl border border-gray-800 bg-gray-800/40">
            <div class="relative">
                <img class="w-20 h-20 rounded-full object-cover border-2 border-red-500" src="{{ asset("storage/vendor/profile/" . $profilePicture) }}" alt="Profile" />
                <span class="absolute bottom-0 right-0 w-4 h-4 rounded-full border-2 border-gray-900 {{ $profile_visibility ? 'bg-green-500' : 'bg-red-500' }}"></span>
            </div>
            <h2 class="mt-3 font-semibold text-lg text-gray-100 text-center truncate w-full" id="full-name-sidebar">{{ $fullName }}</h2>

            <div class="flex items-center gap-1.5 mt-2">
                @if($profile_visibility)
                    <span class="text-xs bg-green-500/10 text-green-400 px-2.5 py-0.5 rounded-full font-medium">Active Store</span>
                @else
                    <span class="text-xs bg-red-500/10 text-red-400 px-2.5 py-0.5 rounded-full font-medium">Closed</span>
                @endif
                <div class="flex text-yellow-500 text-xs ml-1">★ ★ ★ ★ ★</div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-1">
            <a href="{{ route('vendor.dashboard') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fa fa-chart-bar w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('vendor.products') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fa fa-box w-5 text-center"></i> Products
            </a>
            <a href="{{ route('vendor.orders') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fa fa-shopping-cart w-5 text-center"></i> Orders
            </a>
            <a href="{{ route('vendor.replacements.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fa fa-exchange-alt w-5 text-center"></i> Replace Orders
            </a>
            <a href="{{ route('vendor.returns.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fa fa-undo w-5 text-center"></i> Return Orders
            </a>
            <a href="{{ route('vendor.withdraw') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fa fa-wallet w-5 text-center"></i> Withdraw
            </a>
            <a href="{{ route('vendor.notifications') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200 relative">
                <i class="fa fa-bell w-5 text-center"></i> Notifications
                @php $unreadCount = DB::table('notifications')->where('user_id', $user->user_id)->where('is_read', 0)->count(); @endphp
                @if ($unreadCount != 0)
                    <span id="noti-num" class="absolute right-4 w-5 h-5 text-xs bg-red-500 text-white flex justify-center items-center rounded-full font-bold">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('vendor.profile') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fa-solid fa-user w-5 text-center"></i> Profile
            </a>
        </nav>
    </div>

    <!-- Footer Action -->
    <div class="mt-auto pt-6 border-t border-gray-800">
        <a href="#" id="logoutBtn" class="flex items-center gap-3 px-4 py-3 text-red-400 rounded-lg hover:bg-red-500/10 hover:text-red-300 transition-all duration-200">
            <i class="fas fa-sign-out-alt w-5 text-center"></i> Log out
        </a>
    </div>
</aside>

<script>
    function navbarToggle() {
        const button = document.getElementById('btn-side');
        button.classList.toggle('text-white');
        button.classList.toggle('bg-white/85');
        button.classList.toggle('bg-gray-800');

        const aside = document.getElementById('aside');
        const icon = document.getElementById('toggleIcon');
        const backdrop = document.getElementById('sidebarBackdrop');

        aside.classList.toggle('-translate-x-full');

        // Toggle icon between bars and close
        if (aside.classList.contains('-translate-x-full')) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            backdrop.classList.remove('opacity-100', 'pointer-events-auto');
        } else {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-100', 'pointer-events-auto');
        }
    }

    function setActive(page) {
        // Remove active styles from all links
        document.querySelectorAll('nav a.nav-link').forEach(link => {
            link.classList.remove('bg-red-500', 'text-white', 'hover:bg-gray-800');
            link.classList.add('text-gray-400');
        });
        const pageMap = {
            'Dashboard': 'vendor/dashboard',
            'Products': 'vendor/products',
            'Orders': 'vendor/orders',
            'Withdraw': 'vendor/withdraw',
            'Replacements': 'vendor/replacements',
            'Returns': 'vendor/returns',
            'Notifications': 'vendor/notifications',
            'Profile': 'vendor/profile'
        };
        document.querySelectorAll('nav a.nav-link').forEach(link => {
            if (link.href.includes(pageMap[page])) {
                link.classList.add('bg-red-500', 'text-white');
                link.classList.remove('text-gray-400');
            }
        });
    }

    let page = '{{$page}}';
    setActive(page);
</script>

