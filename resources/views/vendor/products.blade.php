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
            :profilePicture="$vendorBasicInfo->profile_picture"
            :fullName="$vendorBasicInfo->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility"
            page='Products'
        />
    
    <!-- Main Content -->
    <main class="flex-1 p-6">
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