<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist | MJ Cheezain</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-customer.theme />
    <style>
        .wishlist-item {
            transition: all 0.3s ease;
        }
        .wishlist-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(232, 93, 133, 0.12);
        }
        .skeleton-loader {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
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
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-customer.sidebar :basic_info="$basic_info"/>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0">
            <x-customer.header title="My Wishlist" subtitle="{{ count($fav) }} saved item(s)" :basic_info="$basic_info" />

            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 pb-28 md:pb-8 page-enter">

                <!-- Sorting and Filter -->
                <div class="flex items-center gap-2 mb-4 -mt-2 md:mt-0 relative z-10 overflow-x-auto no-scrollbar">
                    <select id="sort-op" class="bg-white border border-pink-100 shadow-sm rounded-full px-4 py-2.5 text-xs md:text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-300 flex-shrink-0">
                        <option>Recently Added</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Name: A-Z</option>
                        <option>Name: Z-A</option>
                    </select>
                    <select id="filter-op" class="bg-white border border-pink-100 shadow-sm rounded-full px-4 py-2.5 text-xs md:text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-300 flex-shrink-0">
                        <option>All Items</option>
                        <option>In Stock</option>
                        <option>Limited</option>
                        <option>Out of Stock</option>
                    </select>
                </div>

                <!-- Wishlist Items -->
                <div id="wishlist-con" class="grid grid-cols-1 xl:grid-cols-2 gap-3 md:gap-4"></div>

                <!-- Empty Wishlist State (hidden by default) -->
                <div id="empty-wishlist" class="hidden app-card p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-20 h-20 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-4">
                            <i class="fas fa-heart text-brand text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Your wishlist is empty</h3>
                        <p class="text-gray-500 text-sm mb-6">Save items you love by tapping the heart icon. We'll keep them here for you.</p>
                        <a href="/" class="inline-block px-6 py-3 brand-gradient brand-shadow text-white rounded-full font-semibold text-sm hover:-translate-y-0.5 transition-transform">
                            <i class="fas fa-shopping-bag mr-2"></i>Start Shopping
                        </a>
                    </div>
                </div>

                <!-- Loading Skeleton (hidden by default) -->
                <div id="loading-skeleton" class="hidden app-card p-4">
                    <div class="animate-pulse">
                        <div class="flex space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-28 h-28 bg-pink-100/60 rounded-2xl"></div>
                            </div>
                            <div class="flex-1 space-y-4 py-1">
                                <div class="h-4 bg-pink-100/60 rounded w-3/4"></div>
                                <div class="space-y-2">
                                    <div class="h-3 bg-pink-100/60 rounded"></div>
                                    <div class="h-3 bg-pink-100/60 rounded w-5/6"></div>
                                </div>
                                <div class="h-8 bg-pink-100/60 rounded w-1/3"></div>
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
        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-2xl shadow-lg bg-white border-l-4 ${
                type === 'info' ? 'border-[#FF7DA0]' : type === 'error' ? 'border-red-500' : 'border-green-500'
            } z-50`;
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${
                            type === 'info' ? 'fa-info-circle text-[#FF7DA0]' : type === 'error' ? 'fa-exclamation-circle text-red-500' : 'fa-check-circle text-green-500'
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
            setTimeout(() => notification.remove(), 5000);
        }

        loadWishlist();

        // Sorting and filtering
        document.querySelectorAll('#sort-op, #filter-op').forEach(select => {
            select.addEventListener('change', () => loadWishlist());
        });

        function loadWishlist() {
            const sort = document.querySelector('#sort-op').value;
            const filter = document.querySelector('#filter-op').value;

            document.getElementById('loading-skeleton').classList.remove('hidden');
            const container = document.querySelector('#wishlist-con');
            container.innerHTML = '';

            fetch(`/wishlist/get?sort=${mapSortValue(sort)}&filter=${mapFilterValue(filter)}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('loading-skeleton').classList.add('hidden');
                    container.innerHTML = '';

                    if (data.length === 0) {
                        document.getElementById('empty-wishlist').classList.remove('hidden');
                        return;
                    }

                    document.getElementById('empty-wishlist').classList.add('hidden');

                    data.forEach(product => {
                        const stockCls = product.quantity > 10 ? 'text-green-600 bg-green-100' :
                                         product.quantity > 0 ? 'text-amber-600 bg-amber-100' :
                                         'text-red-600 bg-red-100';
                        const stockLabel = product.quantity > 10 ? 'In Stock' :
                                           product.quantity > 0 ? 'Limited Stock' :
                                           'Out of Stock';
                        const item = `
                        <div class="wishlist-item app-card p-3 md:p-4">
                            <div class="flex">
                                <a href="/product/${product.id}" target="_blank" class="flex-shrink-0 mr-3 md:mr-4">
                                    <img src="/storage/vendor/products/images/${product.image_path || 'default_img.png'}" alt="${product.name}"
                                         class="w-24 h-24 md:w-28 md:h-28 rounded-2xl object-cover border border-pink-50">
                                </a>
                                <div class="flex-1 min-w-0 flex flex-col">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h3 class="text-sm md:text-base font-bold text-gray-900 truncate">${product.name}</h3>
                                            <p class="text-[11px] md:text-sm text-gray-400 truncate">${product.category} • ${product.brand}</p>
                                            <div class="flex text-yellow-400 text-[10px] md:text-xs mt-1">
                                                ${getStars(product.rating || 0)}
                                            </div>
                                        </div>
                                        <button onclick="favToggle(${product.id}, this)" data-product-id="${product.id}"
                                                class="w-9 h-9 flex-shrink-0 flex items-center justify-center bg-pink-50 rounded-full hover:bg-pink-100 transition">
                                            <i class="fas fa-heart text-brand"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-end justify-between mt-auto pt-2">
                                        <div class="min-w-0">
                                            <span class="text-[10px] md:text-xs ${stockCls} px-2 py-0.5 rounded-full font-semibold">${stockLabel}</span>
                                            <div class="mt-1">
                                                <small class="text-gray-400 line-through text-[10px] md:text-xs">Rs. ${product.selling_price}</small>
                                                <span class="text-base md:text-lg font-extrabold text-gray-900 ml-1">Rs. ${product.mrp}</span>
                                            </div>
                                        </div>
                                        <a href="/product/${product.id}" target="_blank"
                                           class="px-3 md:px-4 py-2 brand-gradient brand-shadow text-white rounded-full text-[11px] md:text-sm font-semibold hover:-translate-y-0.5 transition-transform whitespace-nowrap">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
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
        async function favToggle(productId, btn) {
            try {
                const response = await fetch('/favorites/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    // Removed from wishlist — fade the card out and refresh
                    const card = btn ? btn.closest('.wishlist-item') : null;
                    if (card) {
                        card.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                        setTimeout(() => loadWishlist(), 320);
                    } else {
                        loadWishlist();
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error updating favorites', 'error');
            }
        }
    </script>
    <!-- Notification  -->
    <script src="../script/customer_notification.js"></script>
</body>
</html>
