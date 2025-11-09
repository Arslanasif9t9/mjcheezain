@extends('layouts.structure')

@section('title')
    Profile Edit
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

        /* Custom styles for file upload previews and toggle switch */
        .preview-container img {
            max-width: 200px;
            max-height: 200px;
            display: none;
            margin-bottom: 10px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-left: 15px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        #map-preview {
            height: 200px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        /* Custom animation for modal */
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
<body class="bg-gray-50 font-sans">
<!-- In your Blade template --> 
@if(session('success'))
    <x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
    <x-toast type="error" message="{{ session('error') }}" />
@endif

@if(session('warning'))
    <x-toast type="warning" message="{{ session('warning') }}" />
@endif
<x-toast />

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
            <div class="container mx-auto p-4 max-w-4xl">
                
                <x-vendor.progress-bar />
                <x-vendor.tab-navigation />
{{-- {{dd($vendorBasicInfo)}} --}}
                <x-vendor.profile-form 
                    :basicInfo="[
                        'full_name' => $vendorBasicInfo->full_name ?? $user->full_name,
                        'store_name' => $vendorBasicInfo->store_name ?? null,
                        'email' => $vendorBasicInfo->email ?? $user->email,
                        'phone' => $vendorBasicInfo->phone ?? $user->phone,
                        'profile_visibility' => $vendorBasicInfo->profile_visibility ?? 1,
                        'profile_picture' => $vendorBasicInfo->profile_picture ?? 'default_profile.webp'
                    ]"
                    :storeDetails="[
                        'business_type' => $storeDetail->business_type ?? null,
                        'store_category' => $storeDetail->store_category ?? null,
                        'store_description' => $storeDetail->store_description ?? null,
                        'return_policy' => $storeDetail->return_policy ?? null,
                        'shipping_policy' => $storeDetail->shipping_policy ?? null
                    ]"
                    :address="[
                        'pickup_address' => $address->pickup_address ?? null,
                        'city' => $address->city ?? null,
                        'area' => $address->area ?? null,
                        'postal_code' => $address->postal_code ?? null
                    ]"
                    {{-- :current-step="$currentStep" --}}
                    {{-- :active-tab="$activeTab" --}}
                />

            </div>
        </main>
    </div>

    <!-- Logout Modal Component -->
    <x-logout-modal />

    <script src="{{ asset('js/vendor_edit-profile.js') }}"></script>
    <script src="{{ asset('js/vendor_navbar.js') }}"></script>
</body>
@endsection