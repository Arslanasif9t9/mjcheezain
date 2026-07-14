<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist | Multivendor Platform</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- font-awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <style>
        /* Internal CSS */
        .sidebar-item.active {
            background-color: #f3f4f6;
            border-right: 3px solid #3b82f6;
            color: #3b82f6;
        }
        .wishlist-item {
            transition: all 0.3s ease;
        }
        .wishlist-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .empty-wishlist {
            background-image: url("data:image/svg+xml,%3Csvg width='64' height='41' viewBox='0 0 64 41' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M25.5 1C20.5 1 15 4.5 15 12.5C15 20.5 25.5 32 32 38.5C38.5 32 49 20.5 49 12.5C49 4.5 43.5 1 38.5 1C34.1667 1 32 4.5 32 4.5C32 4.5 29.8333 1 25.5 1Z' stroke='%23E5E7EB' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center 30%;
        }
        
        .skeleton-loader {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* For the heart animation */
        @keyframes heartBeat {
            0% { transform: scale(1); }
            14% { transform: scale(1.3); }
            28% { transform: scale(1); }
            42% { transform: scale(1.3); }
            70% { transform: scale(1); }
        }
        
        .heart-animation {
            animation: heartBeat 1s;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-customer.sidebar :basic_info="$basic_info"/>
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <!-- Left side - Mobile menu and title -->
                <div class="flex items-center">
                    <button class="hidden mr-4 text-gray-500 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Wishlist</h1>
                </div>

                <!-- Center - Search bar -->
                <div class="hidden md:flex flex-1 max-w-md mx-4">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search..."
                            class="w-full py-2 pl-4 pr-10 text-sm bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                        <button class="absolute right-3 top-2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Right side - Icons and user menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Notification dropdown -->
                    <div class="relative">
                        <button id="notification-button"
                            class="p-2 text-gray-500 rounded-full hover:bg-gray-100 relative focus:outline-none">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Notification dropdown menu -->
                        <div id="notification-dropdown"
                            class="hidden absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-10 border border-gray-200">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-sm font-medium text-gray-700">Notifications</h3>
                            </div>
                            <div class="divide-y divide-gray-100">
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">New message</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">You received a new message from
                                        Sarah</div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">System update</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">Your system will be updated tonight
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">Payment received</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">Your payment of $29.99 has been
                                        processed</div>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-gray-200 bg-gray-50 text-center">
                                <a href="./notifications.php"
                                    class="text-xs font-medium text-blue-600 hover:text-blue-800">See all
                                    notifications</a>
                            </div>
                        </div>
                    </div>
                    <script src="../script/notification_dropdown.js"></script>

                    <!-- User dropdown -->
                    {{-- <div class="relative">
                        <button id="user-menu-button" class="flex items-center focus:outline-none">
                            <div class="mr-3 text-right hidden sm:block">
                                <span class="block text-sm font-medium text-gray-700"><?= $basic_info['first_name'] . " " . $basic_info['last_name']?></span>
                                <span class="block text-xs text-gray-500">Admin</span>
                            </div>
                            <div class="relative">
                                <img class="w-8 h-8 rounded-full" src="<?= $basic_info['profile_image']?>"
                                    alt="User">
                            </div>
                        </button>
                    </div> --}}
                </div>
            </header>


            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 pb-24 md:pb-6">
                <!-- Wishlist Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Saved Items</h2>
                        <p class="text-gray-600">{{ count($fav) }} items in your wishlist</p>
                    </div>
                </div>
                
                <!-- Wishlist Content -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Sorting and Filter -->
                    <div class="border-b border-gray-200 px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center mb-2 sm:mb-0">
                            <span class="text-sm text-gray-700 mr-2">Sort by:</span>
                            <select class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Recently Added</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                                <option>Name: A-Z</option>
                                <option>Name: Z-A</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-700 mr-2">Filter:</span>
                            <select id="filter-op" class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>All Items</option>
                                <option>In Stock</option>
                                <option>Limited</option>
                                <option>Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Wishlist Items -->
                    <div id="wishlist-con" class="divide-y divide-gray-200">
                        
                    </div>
                    
                    <!-- Empty Wishlist State (hidden by default) -->
                    <div id="empty-wishlist" class="hidden empty-wishlist p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-heart text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">Your wishlist is empty</h3>
                            <p class="text-gray-500 mb-6">Save items you love by clicking the heart icon. We'll keep them here for you.</p>
                            <a href="/">
                                <button class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none">
                                    <i class="fas fa-shopping-bag mr-2"></i>Start Shopping
                                </button>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Loading Skeleton (hidden by default) -->
                    <div id="loading-skeleton" class="hidden p-4">
                        <div class="animate-pulse">
                            <div class="flex space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-32 h-32 bg-gray-200 rounded-lg"></div>
                                </div>
                                <div class="flex-1 space-y-4 py-1">
                                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                    <div class="space-y-2">
                                        <div class="h-3 bg-gray-200 rounded"></div>
                                        <div class="h-3 bg-gray-200 rounded w-5/6"></div>
                                    </div>
                                    <div class="h-8 bg-gray-200 rounded w-1/3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <x-customer.mobile-nav />
        </div>
    </div>
    
    <!-- Internal JavaScript -->
    <script>
        
        // Remove item from wishlist
        function removeFromWishlist(button, productId) {
            const item = button.closest('.wishlist-item');
            item.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            
            setTimeout(() => {
                item.remove();
                updateWishlistCount(-1);
                
                // Check if wishlist is empty
                if (document.querySelectorAll('.wishlist-item').length === 0) {
                    document.getElementById('empty-wishlist').classList.remove('hidden');
                }
            }, 300);
            
            // Show notification
            showNotification('Item removed from your wishlist', 'info');
            
            // In a real app, you would also make an API call to update the server
            console.log(`Removed product ${productId} from wishlist`);
        }
        
        // Toggle item in wishlist (for recently viewed products)
        function toggleWishlist(button, productId) {
            const icon = button.querySelector('i');
            
            if (icon.classList.contains('far')) {
                // Add to wishlist
                icon.classList.remove('far');
                icon.classList.add('fas', 'text-red-500', 'heart-animation');
                showNotification('Item added to your wishlist', 'success');
                
                // In a real app, you would also make an API call to update the server
                console.log(`Added product ${productId} to wishlist`);
            } else {
                // Remove from wishlist
                icon.classList.remove('fas', 'text-red-500');
                icon.classList.add('far');
                showNotification('Item removed from your wishlist', 'info');
                
                // In a real app, you would also make an API call to update the server
                console.log(`Removed product ${productId} from wishlist`);
            }
        }
        
        // Update wishlist count in header
        function updateWishlistCount(change) {
            // This would be connected to your actual wishlist count in a real app
            const countElement = document.querySelector('nav a[href="wishlist.php"]');
            let currentCount = parseInt(countElement.textContent.match(/\((\d+)\)/)[1]) || 0;
            currentCount += change;
            
            if (currentCount <= 0) {
                countElement.textContent = countElement.textContent.replace(/\(\d+\)/, '');
            } else {
                countElement.textContent = countElement.textContent.replace(/\(\d+\)/, `(${currentCount})`);
            }
        }
        
        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg bg-white border-l-4 ${
                type === 'info' ? 'border-blue-500' : 'border-green-500'
            } z-50`;
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${
                            type === 'info' ? 'fa-info-circle text-blue-500' : 'fa-check-circle text-green-500'
                        }"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <div class="ml-4 pl-3 flex-shrink-0 flex">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
        
        // Simulate loading (for demo purposes)
        function simulateLoading() {
            document.getElementById('loading-skeleton').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('loading-skeleton').classList.add('hidden');
            }, 1500);
        }
        
        // Uncomment to test empty wishlist state
        // document.querySelectorAll('.wishlist-item').forEach(item => item.remove());
        // document.getElementById('empty-wishlist').classList.remove('hidden');
        // document.querySelector('nav a[href="wishlist.php"]').textContent = 
        //     document.querySelector('nav a[href="wishlist.php"]').textContent.replace(/\(\d+\)/, '');



        // <!-- get wishlist from php  -->
        // document.addEventListener('DOMContentLoaded', () => {
            loadWishlist();

            // Sorting and filtering
            document.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', () => {
                    loadWishlist();
                });
            });
        // });

        function loadWishlist() {
            const sort = document.querySelector('select:nth-of-type(1)').value;
            const filter = document.querySelector('#filter-op').value;

            document.getElementById('loading-skeleton').classList.remove('hidden');
            document.querySelector('.divide-y').innerHTML = ''; // Clear items

            fetch(`/wishlist/get?sort=${mapSortValue(sort)}&filter=${mapFilterValue(filter)}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('loading-skeleton').classList.add('hidden');
                    let container = document.querySelector('#wishlist-con');
                    container.innerHTML = " ";

                    if (data.length === 0) {
                        document.getElementById('empty-wishlist').classList.remove('hidden');
                        return;
                    }

                    document.getElementById('empty-wishlist').classList.add('hidden');

                    data.forEach(product => {
                        console.log(product);
                        const item = `
                        <div class="wishlist-item p-4 hover:bg-gray-50">
                            <div class="flex flex-col md:flex-row">
                                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-4">
                                    <img src="https://arslan.mjcheezain.com/storage/vendor/products/images/${product.image_path || './uploads/default_img.png'}" alt="${product.name}" class="w-32 h-32 rounded-lg object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">${product.name}</h3>
                                            <p class="text-sm text-gray-500 mb-2">${product.category} • ${product.brand}</p>
                                            <div class="flex items-center mb-2">
                                                <div class="flex text-yellow-400">
                                                    ${getStars(product.rating || 0)}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row sm:flex-col items-center sm:items-end gap-2 mt-2 sm:mt-0">
                                            <span class="text-xl font-bold text-gray-900"><small class="font-normal"><del>$${product.selling_price}</del></small> $${product.mrp}</span>
                                            <span class="text-sm ${
                                                product.quantity > 10 ? 'text-green-600 bg-green-100' : 
                                                product.quantity > 0 ? 'text-yellow-600 bg-yellow-100' : 
                                                'text-red-600 bg-red-100'
                                            } px-2 py-1 rounded-full">
                                                ${
                                                    product.quantity > 10 ? 'In Stock' : 
                                                    product.quantity > 0 ? 'Limited Stock' : 
                                                    'Out of Stock'
                                                }
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-4 gap-4">
                                        <a href="/product/${product.id}" target="_blank" class="flex-1 sm:flex-none">
                                            <button class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none text-center">
                                                <i class="fas fa-eye mr-2"></i>View Details
                                            </button>
                                        </a>
                                        <!-- Heart Icon -->
                                        <button id="heart-btn" onclick="favToggle(${product.id})" data-product-id="${product.id}" class="p-2 bg-white rounded-full shadow-md hover:bg-gray-100 transition flex-shrink-0">
                                            <svg id="heart-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.682l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        container.insertAdjacentHTML('beforeend', item);
                    });
                });
        }

        function getStars(rating) {
            const full = Math.floor(rating);
            const half = rating % 1 >= 0.5 ? 1 : 0;
            return `<i class="fas fa-star"></i>`.repeat(full) +
                (half ? `<i class="fas fa-star-half-alt"></i>` : '') +
                `<i class="far fa-star"></i>`.repeat(5 - full - half);
        }

        function mapSortValue(val) {
            return ({
                'Recently Added': 'recent',
                'Price: Low to High': 'low_high',
                'Price: High to Low': 'high_low',
                'Name: A-Z': 'name_asc',
                'Name: Z-A': 'name_desc'
            })[val] || 'recent';
        }

        function mapFilterValue(val) {
            return ({
                'All Items': 'all',
                'In Stock': 'in_stock',
                'Out of Stock': 'out_of_stock',
                'Limited': 'limited'
            })[val] || 'all';
        }
    </script>
<script>
    async function favToggle(productId){
        console.log('click')
        try {
            // Show loading state
            // heartBtn.disabled = true;
            
            const response = await fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update UI based on favorite status
                // if (data.is_favorite) {
                //     heartIcon.classList.replace('text-gray-700', 'text-red-500');
                //     heartIcon.setAttribute('fill', 'currentColor');
                // } else {
                //     heartIcon.classList.replace('text-red-500', 'text-gray-700');
                //     heartIcon.setAttribute('fill', 'none');
                // }
                
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }

        } catch (error) {
            console.error('Error:', error);
            showNotification('Error updating favorites', 'error');
        } finally {
            // heartBtn.disabled = false;
        }
    }

</script>
{{-- <script src="{{ asset('js/fav.js') }}"></script> --}}
    <!-- Notification  -->
    <script src="../script/customer_notification.js"></script>
</body>
</html>