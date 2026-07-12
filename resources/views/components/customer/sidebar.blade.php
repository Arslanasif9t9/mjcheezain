@props(['basic_info' => null])

<!-- Backdrop Overlay (Hidden on Mobile as sidebar is removed) -->
<div id="customerSidebarBackdrop" class="hidden"></div>

<aside id="customerSidebar" 
       class="hidden md:flex fixed md:sticky top-0 left-0 h-[100vh] w-64 bg-white border-r border-gray-200 flex-col z-50">
    
    <div class="flex items-center justify-between h-16 px-4 bg-blue-600 md:justify-center flex-shrink-0">
        <span class="text-white font-bold text-xl">mjcheezain</span>
        <!-- Close button visible only on mobile -->
        <button onclick="toggleCustomerMobileSidebar()" class="text-white focus:outline-none md:hidden text-lg">
            <i class="fas fa-times"></i>
        </button>
    </div>                
    <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
        <div class="flex items-center px-4 py-3 mb-4 bg-gray-100 rounded-lg">
            <img class="w-10 h-10 rounded-full" src="{{ $basic_info && $basic_info->profile_image ? asset('storage/customer/profile/' . $basic_info->profile_image) : asset('storage/default_profile.webp') }}" alt="User">
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900">{{ $basic_info->first_name ?? '' }} {{ $basic_info->last_name ?? '' }}</p>
                <p class="text-xs text-gray-500">Gold Member</p>
            </div>
        </div>
        
        <nav class="flex-1 space-y-2">
            <a href="/customer/dashboard" class="flex items-center px-4 py-2 text-sm font-medium text-gray-900 rounded-lg sidebar-item hover:bg-gray-100 active">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="/customer/orders" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                <i class="fas fa-shopping-bag mr-3"></i>
                My Orders
            </a>
            <a href="/customer/notifications" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100 relative">
                <i class="fas fa-shopping-bag mr-3"></i>
                Notifications
                @php $unreadCount = $basic_info ? DB::table('notifications')->where('user_id', $basic_info->user_id)->where('is_read', 0)->count() : 0; @endphp
                @if ($unreadCount != 0)
                    <div id="noti-num" class="w-5 h-5 text-sm absolute right-4 bg-red-600 text-white flex justify-center items-center rounded-full">
                        {{ $unreadCount }}
                    </div>
                @endif
            </a>
            <a href="/customer/wishlist" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                <i class="fas fa-heart mr-3"></i>
                Wishlist
            </a>
            <a href="/customer/addresses" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                <i class="fas fa-map-marker-alt mr-3"></i>
                Addresses
            </a>
            <a href="/customer/profile" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                <i class="fas fa-user-cog mr-3"></i>
                Profile Settings
            </a>
        </nav>
        
        <div class="mt-auto mb-4">
            <button id="logoutBtn" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Logout
            </button>
        </div>
    </div>
</aside>

<!-- Premium Logout Modal -->
<div id="logoutModal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
    <!-- Backdrop with blur and gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900/70 via-purple-900/50 to-gray-900/70 backdrop-blur-sm transition-opacity duration-500"></div>
    
    <!-- Modal Container -->
    <div class="relative z-10 w-full max-w-md mx-4 transform transition-all duration-500 opacity-0 scale-95" id="modalContent">
        <!-- Modal Card -->
        <div class="bg-gradient-to-br from-white via-gray-50 to-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200/50">
            <!-- Animated Header -->
            <div class="p-8 bg-gradient-to-r from-red-50 to-pink-50">
                <div class="flex items-center justify-center mb-4">
                    <!-- Animated Icon Container -->
                    <div class="relative">
                        <!-- Outer Ring Animation -->
                        <div class="absolute inset-0 animate-ping bg-red-200 rounded-full opacity-20"></div>
                        <!-- Inner Icon -->
                        <div class="relative w-16 h-16 bg-gradient-to-br from-red-500 to-pink-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-sign-out-alt text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Title with Gradient Text -->
                <h2 class="text-2xl font-bold text-center bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent">
                    Confirm Logout
                </h2>
                <p class="text-gray-600 text-center mt-2">
                    Are you sure you want to sign out?
                </p>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                <!-- Warning Message -->
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-amber-500 mt-1 mr-3"></i>
                        <p class="text-sm text-amber-700">
                            You'll need to sign in again to access your account.
                        </p>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex space-x-4">
                    <!-- Cancel Button -->
                    <button id="cancelLogoutBtn" 
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 transform hover:scale-[1.02] active:scale-95">
                        Cancel
                    </button>
                    
                    <!-- Logout Button with Animation -->
                    <a href="/logout" 
                       class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium rounded-xl shadow-lg hover:shadow-xl hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 flex items-center justify-center group">
                        <span>Yes, Logout</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                </div>
            </div>
            
            <!-- Footer Note -->
            <div class="px-8 py-4 bg-gray-50/50 border-t border-gray-100">
                <p class="text-xs text-gray-500 text-center">
                    <i class="fas fa-lock mr-1"></i>
                    Your session will be securely closed
                </p>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute -top-4 -right-4 w-24 h-24 bg-red-400/10 rounded-full blur-xl"></div>
        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-pink-400/10 rounded-full blur-xl"></div>
    </div>
</div>

<style>
    @keyframes modalFadeIn {
        0% {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes backdropFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .modal-open #logoutModal {
        display: flex !important;
    }
    
    .modal-open #logoutModal > div:first-child {
        animation: backdropFadeIn 0.5s ease-out forwards;
    }
    
    .modal-open #modalContent {
        animation: modalFadeIn 0.5s ease-out 0.1s forwards;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logoutBtn');
    const cancelBtn = document.getElementById('cancelLogoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const modalContent = document.getElementById('modalContent');
    
    // Open modal function
    function openLogoutModal() {
        document.body.classList.add('modal-open');
        logoutModal.classList.remove('hidden');
        
        // Trigger animations
        setTimeout(() => {
            modalContent.classList.remove('opacity-0', 'scale-95');
        }, 10);
    }
    
    // Close modal function
    function closeLogoutModal() {
        // Add closing animation
        modalContent.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            logoutModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
            // modalContent.classList.add('opacity-0', 'scale-95');
        }, 300);
    }
    
    // Event Listeners
    logoutBtn.addEventListener('click', openLogoutModal);
    cancelBtn.addEventListener('click', closeLogoutModal);
    
    // Close modal when clicking outside content
    logoutModal.addEventListener('click', function(e) {
        if (e.target === logoutModal) {
            closeLogoutModal();
        }
    });
    
    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
            closeLogoutModal();
        }
    });
    
    // Prevent default on logout link to handle animation
    document.querySelector('a[href="/logout"]').addEventListener('click', function(e) {
        // Optional: Add loading animation before redirect
        const logoutBtn = this;
        const originalText = logoutBtn.innerHTML;
        
        logoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Logging out...';
        logoutBtn.classList.add('opacity-75', 'cursor-not-allowed');
        
        // Optional: Add a small delay for better UX
        setTimeout(() => {
            // Allow the redirect to happen
        }, 500);
    });
    
    function toggleCustomerMobileSidebar() {
        const sidebar = document.getElementById('customerSidebar');
        const backdrop = document.getElementById('customerSidebarBackdrop');
        if (!sidebar || !backdrop) return;
        
        sidebar.classList.toggle('-translate-x-full');
        
        if (sidebar.classList.contains('-translate-x-full')) {
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            backdrop.classList.remove('opacity-100', 'pointer-events-auto');
        } else {
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-100', 'pointer-events-auto');
        }
    }
    window.toggleCustomerMobileSidebar = toggleCustomerMobileSidebar;

    function activateSidebarLink() {
        // Get current URL path
        const currentPath = window.location.pathname;
        
        // Remove active class from all sidebar items first
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.classList.remove('active', 'bg-blue-50', 'text-blue-700');
            item.classList.add('text-gray-700');
        });
        
        // Define route patterns and their corresponding sidebar links
        const routePatterns = [
            { pattern: /^\/customer\/dashboard/, selector: 'a[href="/customer/dashboard"]' },
            { pattern: /^\/customer\/orders/, selector: 'a[href="/customer/orders"]' },
            { pattern: /^\/customer\/notifications?/, selector: 'a[href="/customer/notifications"]' },
            { pattern: /^\/customer\/wishlist/, selector: 'a[href="/customer/wishlist"]' },
            { pattern: /^\/customer\/addresses/, selector: 'a[href="/customer/addresses"]' },
            { pattern: /^\/customer\/profile/, selector: 'a[href="/customer/profile"]' }
        ];
        
        // Find and activate the matching sidebar item
        let activeFound = false;
        
        for (const route of routePatterns) {
            if (route.pattern.test(currentPath)) {
                const activeLink = document.querySelector(route.selector);
                if (activeLink) {
                    activeLink.classList.add('active', 'bg-blue-50', 'text-blue-700');
                    activeLink.classList.remove('text-gray-700');
                    activeFound = true;
                    break;
                }
            }
        }
        
        // If no specific match found, try exact href match as fallback
        if (!activeFound) {
            const exactMatch = document.querySelector(`a[href="${currentPath}"]`);
            if (exactMatch) {
                exactMatch.classList.add('active', 'bg-blue-50', 'text-blue-700');
                exactMatch.classList.remove('text-gray-700');
            }
        }
    }
    
    // Initialize sidebar activation
    activateSidebarLink();
    
    // Find any hamburger menu button in the page header
    const headerToggleBtns = document.querySelectorAll('header button');
    headerToggleBtns.forEach(btn => {
        const icon = btn.querySelector('.fa-bars');
        if (icon) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleCustomerMobileSidebar();
            });
        }
    });

    // Optional: Add click handler to update active state immediately on click
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            document.querySelectorAll('.sidebar-item').forEach(navItem => {
                navItem.classList.remove('active', 'bg-blue-50', 'text-blue-700');
                navItem.classList.add('text-gray-700');
            });
            
            // Add active class to clicked item
            this.classList.add('active', 'bg-blue-50', 'text-blue-700');
            this.classList.remove('text-gray-700');
            
            // Close mobile sidebar on click if open
            const sidebar = document.getElementById('customerSidebar');
            if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                toggleCustomerMobileSidebar();
            }
        });
    });
});
</script>