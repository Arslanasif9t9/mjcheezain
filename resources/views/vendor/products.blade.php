@extends('layouts.structure')
@section('title')
    Products
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor_product.css') }}">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .tab-active-v {
            border-bottom: 2px solid #E85D85 !important;
            color: #E85D85 !important;
        }
        /* Mobile pill tabs: active state overrides the underline style */
        .v-pill.tab-active-v {
            border: 1px solid transparent !important;
            color: #fff !important;
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%) !important;
            box-shadow: 0 4px 12px rgba(255, 125, 160, 0.35);
        }

        #logoutModal {
        animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
        }
    </style>
@endsection

@section('body')
<div class="flex min-h-screen">
    <!-- Sidebar Component -->
    <x-vendor.sidebar
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name ?? ''"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            :user="$user"
            page='Products'
        />

    <!-- Main Content -->
    <main class="flex-1 min-w-0 bg-[#FFF6F0]">
        <!-- Mobile App Header (md:hidden) -->
        <x-vendor.app-header title="My Products" subtitle="Manage your catalog" />

        <div class="p-4 md:p-6 pb-28 md:pb-8 page-enter">
            <!-- Products Header Component (desktop only) -->
            <div class="hidden md:block">
                <x-vendor.products-header
                    :completionPercentage="$completion_percentage"
                    :activeTab="$active_tab"
                    :totalProducts="$total_products"
                    :pendingProducts="$pending_products" />
            </div>

            <!-- Mobile toolbar: search + add + progress + pill tabs -->
            <div class="md:hidden mb-4 space-y-3">
                <div class="flex items-stretch gap-2">
                    <div class="relative flex-1 min-w-0">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                        </span>
                        <input type="text" id="productSearchMobile" placeholder="Search products..."
                               class="w-full h-11 pl-10 pr-4 rounded-full border border-pink-100 bg-white text-sm outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 shadow-sm transition-all" />
                    </div>
                    <div class="relative flex-shrink-0" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            @click.away="open = false"
                            class="h-11 px-4 rounded-full text-white text-sm font-bold flex items-center gap-1.5 active:scale-95 transition-transform"
                            style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 14px rgba(255, 125, 160, 0.35);">
                            <i class="fas fa-plus text-xs"></i> Add
                        </button>
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 z-30 border border-pink-100 overflow-hidden"
                            style="display: none;">
                            <a href="{{ route('vendor.products.autoparts.create') }}"
                               class="block px-4 py-2.5 text-sm text-gray-700 hover:text-[#E85D85] font-medium border-b border-gray-50 transition-colors">
                                🚗 Auto Parts
                            </a>
                            <a href="{{ route('vendor.products.create') }}"
                               class="block px-4 py-2.5 text-sm text-gray-700 hover:text-[#E85D85] font-medium transition-colors">
                                📦 Other Products
                            </a>
                        </div>
                    </div>
                </div>

                <div class="app-card px-4 py-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[11px] font-semibold text-gray-500">Product Setup Progress</span>
                        <span class="text-[11px] font-bold text-[#E85D85]">{{ $completion_percentage }}% Done</span>
                    </div>
                    <div class="w-full h-1.5 bg-pink-50 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-300" style="background: linear-gradient(90deg, #FF7DA0, #FFC275); width: {{ $completion_percentage }}%"></div>
                    </div>
                </div>

                <div class="flex gap-2 overflow-x-auto scrollbar-hide -mx-4 px-4 pb-1">
                    @foreach (['online' => 'Online', 'pending' => 'Pending', 'offline' => 'Offline', 'draft' => 'Draft', 'all' => 'All'] as $tKey => $tLabel)
                        <button type="button" data-tab="{{ $tKey }}"
                                class="tab-button v-pill flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold bg-white border border-pink-100 text-gray-600 whitespace-nowrap transition">
                            {{ $tLabel }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Products Table Component (desktop only) -->
            <div class="hidden md:block">
                <x-vendor.products-table :products="$products" :active-tab="$active_tab" />
            </div>

            <!-- Mobile product card list -->
            <div class="md:hidden space-y-3">
                @if(count($products) > 0)
                    @foreach($products as $product)
                        @php
                            $m_position_map = [
                                'online' => 'Online',
                                'approved' => 'Online',
                                'pending' => 'Pending',
                                'offline' => 'Offline',
                                'rejected' => 'Offline',
                                'draft' => 'Draft'
                            ];
                            if ($product->quantity > 10) {
                                $m_status_class = 'bg-green-100 text-green-700';
                                $m_status_text = 'In Stock';
                            } elseif ($product->quantity > 0) {
                                $m_status_class = 'bg-yellow-100 text-yellow-700';
                                $m_status_text = 'Limited';
                            } else {
                                $m_status_class = 'bg-red-100 text-red-700';
                                $m_status_text = 'Out of Stock';
                            }
                            $m_prd_code = \App\Support\RefId::product($product->id);
                        @endphp
                        <div class="product-card-m app-card p-3"
                             data-position="{{ $product->position ?? 'all' }}"
                             data-product-id="{{ $product->id }}"
                             data-search="{{ strtolower($product->name . ' ' . $product->category . ' ' . $m_prd_code) }}">
                            <div class="flex gap-3">
                                <a href="/product/{{ $product->id }}" target="_blank" class="flex-shrink-0">
                                    <img src="{{ $product->primary_image ? asset('storage/vendor/products/images/'.$product->primary_image) : asset('img/default_img.png') }}"
                                         alt="{{ $product->name }}"
                                         class="w-16 h-16 rounded-xl object-cover border border-pink-50">
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="/product/{{ $product->id }}" target="_blank">
                                        <p class="text-sm font-bold text-gray-800 leading-snug line-clamp-2">{{ $product->name }}</p>
                                    </a>
                                    <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $product->category }} &middot; {{ $m_prd_code }}</p>
                                    <p class="text-sm font-extrabold text-[#E85D85] mt-1">Rs. {{ number_format($product->selling_price * 1.17, 2) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-pink-50">
                                <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                    <span class="{{ $m_status_class }} px-2 py-0.5 text-[10px] font-semibold rounded-full">{{ $m_status_text }}</span>
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 text-[10px] font-semibold rounded-full">Stock: {{ $product->quantity }}</span>
                                    <span class="brand-gradient-soft text-[#E85D85] px-2 py-0.5 text-[10px] font-semibold rounded-full">{{ $m_position_map[$product->position] ?? ucfirst($product->position ?? '') }}</span>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                    <a href="{{ route('vendor.products.edit', $product->id) }}"
                                       class="w-8 h-8 rounded-full bg-pink-50 text-[#E85D85] border border-pink-100 flex items-center justify-center active:scale-90 transition-transform" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('vendor.products.delete') }}" class="delete-product-form-m inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 flex items-center justify-center active:scale-90 transition-transform" title="Delete">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div id="mProductsEmptyFilter" class="hidden text-center py-10">
                        <div class="w-16 h-16 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-3">
                            <i class="fas fa-box-open text-2xl text-[#E85D85]"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No products found</p>
                    </div>
                @else
                    <div class="app-card text-center py-10 px-4">
                        <div class="w-16 h-16 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-3">
                            <i class="fas fa-box-open text-2xl text-[#E85D85]"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No products found</p>
                        <a href="{{ route('vendor.products.create') }}"
                           class="inline-block mt-3 px-5 py-2 rounded-full text-white text-sm font-semibold"
                           style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 14px rgba(255, 125, 160, 0.35);">
                            Add your first product
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<x-logout-modal />

    <script>
        // DB position values -> tab keys (legacy tabs use online/offline naming)
        window.mjPositionMatchesTab = function (position, tab) {
            const alias = { approved: 'online', rejected: 'offline' };
            return tab === 'all' || position === tab || alias[position] === tab;
        };

        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const productRows = document.querySelectorAll('.product-row');

            // Set initial active tab
            let activeTab = 'all';
            updateActiveTab(activeTab);
            filterProducts(activeTab);

            // Add click event listeners to tabs
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    activeTab = this.getAttribute('data-tab');
                    updateActiveTab(activeTab);
                    filterProducts(activeTab);
                });
            });

            // Function to update active tab styling
            function updateActiveTab(tab) {
                tabButtons.forEach(button => {
                    if (button.getAttribute('data-tab') === tab) {
                        button.classList.add('tab-active-v');
                        button.classList.remove('text-gray-600');
                    } else {
                        button.classList.remove('tab-active-v');
                        button.classList.add('text-gray-600');
                    }
                });
            }

            // Function to filter products based on active tab
            function filterProducts(tab) {
                productRows.forEach(row => {
                    const position = row.getAttribute('data-position');

                    if (window.mjPositionMatchesTab(position, tab)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('productSearch');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    filterProductsBySearch(searchTerm);
                });
            }

            function filterProductsBySearch(searchTerm) {
                const productRows = document.querySelectorAll('.product-row');
                const activeTab = document.querySelector('.tab-button.tab-active-v').getAttribute('data-tab');

                productRows.forEach(row => {
                    const productName = row.querySelector('.font-semibold').textContent.toLowerCase();
                    const productCategory = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const productId = row.querySelector('td:first-child a').textContent.toLowerCase();

                    const matchesSearch = searchTerm === '' ||
                        productName.includes(searchTerm) ||
                        productCategory.includes(searchTerm) ||
                        productId.includes(searchTerm);

                    const position = row.getAttribute('data-position');
                    const matchesTab = window.mjPositionMatchesTab(position, activeTab);

                    if (matchesSearch && matchesTab) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("productSearch");
            const tableRows = document.querySelectorAll("table tbody tr");

            if (searchInput && tableRows.length > 0) {
                searchInput.addEventListener("keyup", function () {
                    const searchTerm = searchInput.value.toLowerCase();

                    tableRows.forEach((row) => {
                        const rowText = row.textContent.toLowerCase();
                        if (rowText.includes(searchTerm)) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });
                });
            }
        });
    </script>

    <script>
        // Mobile card list: tab filtering, search and delete (mirrors desktop behaviour)
        document.addEventListener('DOMContentLoaded', function () {
            const mCards = Array.from(document.querySelectorAll('.product-card-m'));
            const mEmpty = document.getElementById('mProductsEmptyFilter');
            let mTab = 'all';
            let mSearch = '';

            function applyMobileFilters() {
                let visible = 0;
                mCards.forEach(card => {
                    if (!card.isConnected) return;
                    const position = card.getAttribute('data-position');
                    const hay = (card.getAttribute('data-search') || '');
                    const matchesTab = window.mjPositionMatchesTab(position, mTab);
                    const matchesSearch = mSearch === '' || hay.includes(mSearch);
                    const show = matchesTab && matchesSearch;
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                if (mEmpty) mEmpty.classList.toggle('hidden', visible > 0 || mCards.length === 0);
            }

            // Pill tabs share .tab-button with the desktop tabs, so the existing
            // handler keeps active styling in sync; this one filters the cards.
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function () {
                    mTab = this.getAttribute('data-tab');
                    applyMobileFilters();
                });
            });

            const mSearchInput = document.getElementById('productSearchMobile');
            if (mSearchInput) {
                mSearchInput.addEventListener('input', function () {
                    mSearch = this.value.toLowerCase().trim();
                    applyMobileFilters();
                });
            }

            applyMobileFilters();

            // Mobile delete: same endpoint/payload as the desktop .delete-product-form handler
            document.querySelectorAll('.delete-product-form-m').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (!confirm('Are you sure you want to delete this product?')) {
                        return;
                    }

                    const productId = form.querySelector('input[name="product_id"]').value;
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const deleteButton = form.querySelector('button[type="submit"]');
                    const originalHTML = deleteButton.innerHTML;
                    deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    deleteButton.disabled = true;

                    fetch(form.getAttribute('action'), {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Failed to delete product');
                        }
                        const card = form.closest('.product-card-m');
                        card.style.transition = 'opacity 0.3s ease';
                        card.style.opacity = '0';
                        setTimeout(() => {
                            const idx = mCards.indexOf(card);
                            if (idx > -1) mCards.splice(idx, 1);
                            card.remove();
                            applyMobileFilters();
                        }, 300);

                        // Keep the desktop table in sync
                        document.querySelectorAll('.delete-product-form input[name="product_id"]').forEach(inp => {
                            if (inp.value === productId) {
                                const row = inp.closest('tr');
                                if (row) row.remove();
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message || 'Failed to delete product');
                        deleteButton.innerHTML = originalHTML;
                        deleteButton.disabled = false;
                    });
                });
            });
        });
    </script>
@endsection
