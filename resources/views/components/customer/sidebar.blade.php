@props(['basic_info' => null])

<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-white border-r border-gray-200">
        <div class="flex items-center justify-center h-16 px-4 bg-blue-600">
            <span class="text-white font-bold text-xl">mjcheezain</span>
        </div>                
        <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
            <div class="flex items-center px-4 py-3 mb-4 bg-gray-100 rounded-lg">
                <img class="w-10 h-10 rounded-full" src="{{ $basic_info->profile_image ? asset('storage/customer/profile/' . $basic_info->profile_image) : asset('storage/default_profile.webp') }}" alt="User">
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
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50" id="logoutBtn">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    
    // Optional: Re-activate when navigating via AJAX or SPA (if needed)
    // You can call activateSidebarLink() after AJAX page loads
    
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
        });
    });
});
</script>