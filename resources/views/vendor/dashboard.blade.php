@extends('layouts.structure')
@section('title')
    Dashboard UI
@endsection

@section('style')
    <style>
        canvas {
            max-width: 600px;
            max-height: 400px;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        #logoutModal {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection

@section('body')
<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar Component -->
        {{-- @if (!$vendorBasicInfo->profile_picture)  --}}
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            :user="$user"
            page='Dashboard'
        />

        <!-- Main Content -->
        <main class="flex-1 min-w-0 bg-[#FFF6F0]">
            <!-- Mobile App Header (md:hidden) -->
            <x-vendor.app-header title="Dashboard" subtitle="Here's how your store is doing" />

            <div class="p-4 md:p-6 pb-28 md:pb-8 page-enter">
                <!-- Header Component (desktop only) -->
                <x-vendor.header :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'" />

                <!-- Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">
                    <!-- Chart Component -->
                    <x-vendor.chart :labels="$chartLabels" :data="$chartData" />

                    <!-- Balance Component -->
                    <x-vendor.balance :balance="$balance" />
                </div>

                <!-- Stats Cards Component -->
                <x-vendor.stats-cards
                    :totalProducts="$totalProducts"
                    :totalSales="$totalSales"
                    :newOrders="$newOrders"
                />

                <!-- Recent Sold & Top Categories -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
                    <!-- Recent Sold Component -->
                    <x-vendor.recent-sold :recentOrders="$recentOrders" />

                    <!-- Top Categories Component -->
                    <x-vendor.top-categories :topCategories="$topCategories" />
                </div>
            </div>
        </main>
    </div>

    <!-- Logout Modal Component -->
    <x-logout-modal />
</body>
@endsection