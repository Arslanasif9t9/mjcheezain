@props(['profilePicture', 'fullName', 'profile_visibility', 'page', 'user'])

<style>
    .v-brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }
    .v-nav-active {
        background: linear-gradient(115deg, rgba(255, 125, 160, 0.14) 0%, rgba(255, 194, 117, 0.14) 100%);
        color: #E85D85 !important;
        border-right: 3px solid #E85D85;
        font-weight: 600;
    }
</style>

<!-- Sidebar Toggle Button -->
<button id="btn-side" onclick="navbarToggle()"
    class="md:hidden fixed top-4 left-4 m-2 z-[10002] text-gray-700 hover:text-[#E85D85] transition-colors duration-200 text-2xl bg-white/90 backdrop-blur-md p-2 rounded-xl shadow-md flex items-center justify-center w-10 h-10 border border-pink-100">
    <i id="toggleIcon" class="fas fa-bars"></i>
</button>

<!-- Backdrop Overlay for Mobile -->
<div id="sidebarBackdrop" onclick="navbarToggle()"
     class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300 opacity-0 pointer-events-none z-[10000] md:hidden"></div>

<aside id="aside"
    class="fixed md:sticky top-0 left-0 h-[100vh] w-64 bg-white border-r border-pink-100 transform -translate-x-full transition-transform duration-300 md:translate-x-0 z-[10001] shadow-xl md:shadow-none flex flex-col">

    <!-- Brand/Header -->
    <div class="flex items-center gap-2.5 h-16 px-5 v-brand-gradient flex-shrink-0 relative overflow-hidden">
        <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full pointer-events-none"></div>
        <span class="text-white font-extrabold text-2xl tracking-wider relative z-10">MJ</span>
        <span class="font-bold text-white text-base tracking-normal relative z-10">Vendor Center</span>
    </div>

    <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
        <!-- Profile Info -->
        <a href="{{ route('vendor.profile') }}" class="flex flex-col items-center mb-5 p-4 rounded-2xl border border-pink-100 hover:shadow-md transition-shadow no-underline"
           style="background: linear-gradient(115deg, rgba(255, 125, 160, 0.10) 0%, rgba(255, 194, 117, 0.10) 100%);">
            <div class="relative">
                <img class="w-16 h-16 rounded-full object-cover ring-2 ring-white shadow" src="{{ asset("storage/vendor/profile/" . $profilePicture) }}" alt="Profile" />
                <span class="absolute bottom-0 right-0 w-4 h-4 rounded-full border-2 border-white {{ $profile_visibility ? 'bg-green-500' : 'bg-red-500' }}"></span>
            </div>
            <h2 class="mt-2.5 font-bold text-sm text-gray-900 text-center truncate w-full" id="full-name-sidebar">{{ $fullName }}</h2>

            <div class="flex items-center gap-1.5 mt-1.5">
                @if($profile_visibility)
                    <span class="text-[10px] bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-bold">Active Store</span>
                @else
                    <span class="text-[10px] bg-red-100 text-red-500 px-2 py-0.5 rounded-full font-bold">Closed</span>
                @endif
                <div class="flex text-yellow-400 text-[10px]">★ ★ ★ ★ ★</div>
            </div>
        </a>

        <!-- Navigation Links -->
        <nav class="space-y-1 flex-1">
            <a href="{{ route('vendor.dashboard') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-gray-900 transition-all duration-200">
                <i class="fa fa-chart-bar w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('vendor.products') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-gray-900 transition-all duration-200">
                <i class="fa fa-box w-5 text-center"></i> Products
            </a>
            <a href="{{ route('vendor.orders') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-gray-900 transition-all duration-200">
                <i class="fa fa-shopping-cart w-5 text-center"></i> Orders
            </a>
            <a href="{{ route('vendor.replacements.index') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-gray-900 transition-all duration-200">
                <i class="fa fa-exchange-alt w-5 text-center"></i> Replace Orders
            </a>
            <a href="{{ route('vendor.returns.index') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-gray-900 transition-all duration-200">
                <i class="fa fa-undo w-5 text-center"></i> Return Orders
            </a>
            <a href="{{ route('vendor.withdraw') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-gray-900 transition-all duration-200">
                <i class="fa fa-wallet w-5 text-center"></i> Withdraw
            </a>
            <a href="{{ route('vendor.notifications') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-gray-900 transition-all duration-200 relative">
                <i class="fa fa-bell w-5 text-center"></i> Notifications
                @php $unreadCount = DB::table('notifications')->where('user_id', $user->user_id ?? auth()->id())->where('is_read', 0)->count(); @endphp
                @if ($unreadCount != 0)
                    <span id="noti-num" class="absolute right-3 min-w-[20px] h-5 px-1 text-xs v-brand-gradient text-white flex justify-center items-center rounded-full font-bold shadow">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('vendor.profile') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-gray-900 transition-all duration-200">
                <i class="fa-solid fa-user w-5 text-center"></i> Profile
            </a>
        </nav>

        <!-- Footer Action -->
        <div class="mt-auto pt-4 border-t border-pink-50 space-y-1.5">
            <a href="/" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-white rounded-xl v-brand-gradient hover:opacity-90 transition-all duration-200 no-underline" style="box-shadow: 0 4px 12px rgba(255, 125, 160, 0.3);">
                <i class="fas fa-store w-5 text-center"></i> View Site
            </a>
            <a href="#" id="logoutBtn" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-500 rounded-xl hover:bg-red-50 transition-all duration-200 no-underline">
                <i class="fas fa-sign-out-alt w-5 text-center"></i> Log out
            </a>
        </div>
    </div>
</aside>

<script>
    function navbarToggle() {
        const button = document.getElementById('btn-side');
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
        document.querySelectorAll('nav a.nav-link').forEach(link => {
            link.classList.remove('v-nav-active');
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
            if (pageMap[page] && link.href.includes(pageMap[page])) {
                link.classList.add('v-nav-active');
            }
        });
    }

    let page = '{{$page}}';
    setActive(page);
</script>
