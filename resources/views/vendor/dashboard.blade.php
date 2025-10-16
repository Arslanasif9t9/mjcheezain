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
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture"
            :fullName="$vendorBasicInfo->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility"
            page='Dashboard'
        />

        <!-- Main Content -->
        <main class="bg-gray-100 flex-1 p-6 overflow-y-auto scrollbar-hide">
            <!-- Header Component -->
            <x-vendor.header :profilePicture="$vendorBasicInfo->profile_picture" />

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Sold Component -->
                <x-vendor.recent-sold :recentOrders="$recentOrders" />
                
                <!-- Top Categories Component -->
                <x-vendor.top-categories :topCategories="$topCategories" />
            </div>
        </main>
    </div>

    <!-- Logout Modal Component -->
    <x-logout-modal />

    <script src="../script/logout.js"></script>
    <script src="../script/vendor_navbar.js"></script>
    <script src="../script/notification.js"></script>
</body>
@endsection