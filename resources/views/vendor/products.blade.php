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
        .tab-active-v {
            border-bottom: 2px solid #E85D85 !important;
            color: #E85D85 !important;
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
    <main class="flex-1 p-4 sm:p-6 pt-16 sm:pt-6">
        <!-- Products Header Component -->
        <x-vendor.products-header 
            :completionPercentage="$completion_percentage"
            :activeTab="$active_tab"
            :totalProducts="$total_products"
            :pendingProducts="$pending_products" />
        
        <!-- Products Table Component -->
        <x-vendor.products-table :products="$products" :active-tab="$active_tab" />
    </main>
</div>

<x-logout-modal />

    <script>
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
                    
                    if (tab === 'all' || position === tab) {
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
                    const matchesTab = activeTab === 'all' || position === activeTab;
                    
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
@endsection