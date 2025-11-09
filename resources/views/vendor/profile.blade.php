@extends('layouts.structure')

@section('title')
    Profile
@endsection

@section('style')
    <style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .verified-badge {
        background-color: #22c55e;
        color: white;
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 8px;
    }
    </style>
@endsection

@section('body')
<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar Component -->
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            page='Profile'
        />

        <!-- Main Content -->
        <main class="bg-gray-100 flex-1 p-6 overflow-y-auto scrollbar-hide">
            <!-- Profile Header Component -->
            <x-vendor.profile-header 
                :storeLogo="$store_logo"
                :storeName="$store_name"
                :rating="$rating"
                :verified="$verified"
                :city="$city"
                :country="$country"
                :storeBanner="$store_banner"
            />

            <!-- Content Grid -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Info Component -->
                <x-vendor.personal-info 
                    :fullName="$full_name"
                    :email="$user_email"
                    :phone="$phone"
                    :address="$pickup_address"
                />
                
                <!-- Store Info Component -->
                <x-vendor.store-info 
                    :businessType="$business_type"
                    :storeCategory="$store_category"
                    :returnPolicy="$return_policy"
                    :returnPolicyFile="$return_policy_file"
                    :shippingPolicy="$shipping_policy"
                    :shippingPolicyFile="$shipping_policy_file"
                    :storeDescription="$store_description"
                    :storeBanner="$store_banner"
                />
                
                <!-- Profile Address Component -->
                <x-vendor.profile-address 
                    :pickupAddress="$pickup_address"
                    :city="$city"
                    :area="$area"
                    :country="$country"
                    :postalCode="$postal_code"
                />
            </div>
        </main>
    </div>

    <!-- Logout Modal Component -->
    <x-logout-modal />

    <script src="{{ asset('script/vendor_navbar.js') }}"></script>
    <script src="{{ asset('script/notification.js') }}"></script>
</body>
@endsection