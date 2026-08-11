@php
    $adminNavItems = [
        ['href' => '/admin/dashboard',          'icon' => 'fa-house',        'label' => 'Dashboard',         'match' => 'admin/dashboard*'],
        ['href' => '/admin/vendors',            'icon' => 'fa-store',        'label' => 'Vendors',           'match' => 'admin/vendors*'],
        ['href' => '/admin/customers',          'icon' => 'fa-users',        'label' => 'Customers',         'match' => 'admin/customers*'],
        ['href' => '/admin/products',           'icon' => 'fa-box',          'label' => 'Products',          'match' => 'admin/products*'],
        ['href' => '/admin/orders',             'icon' => 'fa-truck',        'label' => 'Orders',            'match' => 'admin/orders*'],
        ['href' => '/admin/returns',            'icon' => 'fa-rotate-left',  'label' => 'Returns',           'match' => 'admin/returns*'],
        ['href' => '/admin/replacements',       'icon' => 'fa-right-left',   'label' => 'Replacements',      'match' => 'admin/replacements*'],
        ['href' => '/admin/withdraw-requests',  'icon' => 'fa-wallet',       'label' => 'Withdraw Requests', 'match' => 'admin/withdraw-requests*'],
        ['href' => '/admin/categories',         'icon' => 'fa-tags',         'label' => 'Categories',        'match' => 'admin/categories*'],
        ['href' => '/admin/category-suggestions','icon' => 'fa-lightbulb',   'label' => 'Category Requests', 'match' => 'admin/category-suggestions*'],
        ['href' => '/admin/controls',            'icon' => 'fa-sliders',      'label' => 'Controls',           'match' => 'admin/controls*'],
    ];
@endphp

<style>
    .adm-brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }
    .adm-nav-link {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 16px; border-radius: 12px;
        font-size: 0.875rem; font-weight: 500; color: #4b5563;
        text-decoration: none; transition: all .2s ease;
    }
    .adm-nav-link:hover { background: #FDF2F5; color: #111827; text-decoration: none; }
    .adm-nav-link.adm-active {
        background: linear-gradient(115deg, rgba(255, 125, 160, 0.14) 0%, rgba(255, 194, 117, 0.14) 100%);
        color: #E85D85 !important;
        border-right: 3px solid #E85D85;
        font-weight: 600;
    }
    .adm-nav-link i { width: 20px; text-align: center; margin: 0; }
    #adminSidebar { font-family: 'Poppins', sans-serif; }
</style>

<!-- Mobile toggle button -->
<button id="adminSidebarBtn" type="button" onclick="adminSidebarToggle()"
    class="md:hidden fixed top-4 left-4 z-[10002] text-gray-700 hover:text-[#E85D85] transition-colors duration-200 text-xl bg-white/90 backdrop-blur-md p-2 rounded-xl shadow-md flex items-center justify-center w-10 h-10 border border-pink-100">
    <i id="adminSidebarIcon" class="fas fa-bars"></i>
</button>

<!-- Backdrop for mobile drawer -->
<div id="adminSidebarBackdrop" onclick="adminSidebarToggle()"
     class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300 opacity-0 pointer-events-none z-[10000] md:hidden"></div>

<aside id="adminSidebar"
    class="fixed md:sticky top-0 left-0 h-screen w-64 bg-white border-r border-pink-100 transform -translate-x-full transition-transform duration-300 md:translate-x-0 z-[10001] shadow-xl md:shadow-none flex flex-col flex-shrink-0">

    <!-- Brand header -->
    <div class="flex items-center gap-2.5 h-16 px-5 adm-brand-gradient flex-shrink-0 relative overflow-hidden">
        <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full pointer-events-none"></div>
        <span class="text-white font-extrabold text-2xl tracking-wider relative z-10">MJ</span>
        <span class="font-bold text-white text-base relative z-10">Admin Panel</span>
    </div>

    <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
        <!-- Admin badge -->
        <div class="flex items-center gap-3 mb-4 p-3 rounded-2xl border border-pink-100"
             style="background: linear-gradient(115deg, rgba(255, 125, 160, 0.10) 0%, rgba(255, 194, 117, 0.10) 100%);">
            <div class="w-10 h-10 rounded-full adm-brand-gradient flex items-center justify-center text-white shadow flex-shrink-0">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="min-w-0">
                <div class="font-bold text-sm text-gray-900 truncate">{{ Session::get('admin_username', 'Administrator') }}</div>
                <div class="text-[11px] text-[#E85D85] font-semibold">Super Admin</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="space-y-1 flex-1">
            @foreach ($adminNavItems as $item)
                <a href="{{ $item['href'] }}" class="adm-nav-link {{ request()->is($item['match']) ? 'adm-active' : '' }}">
                    <i class="fa-solid {{ $item['icon'] }}"></i> {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Footer -->
        <div class="mt-auto pt-4 border-t border-pink-50 space-y-1.5">
            <a href="/" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-white rounded-xl adm-brand-gradient hover:opacity-90 transition-all duration-200 no-underline"
               style="box-shadow: 0 4px 12px rgba(255, 125, 160, 0.3); text-decoration: none;">
                <i class="fas fa-globe w-5 text-center"></i> View Site
            </a>
            <a href="/admin/logout" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-500 rounded-xl hover:bg-red-50 transition-all duration-200 no-underline" style="text-decoration: none;">
                <i class="fas fa-sign-out w-5 text-center"></i> Logout
            </a>
        </div>
    </div>
</aside>

<script>
    function adminSidebarToggle() {
        var aside = document.getElementById('adminSidebar');
        var icon = document.getElementById('adminSidebarIcon');
        var backdrop = document.getElementById('adminSidebarBackdrop');

        aside.classList.toggle('-translate-x-full');

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
</script>
