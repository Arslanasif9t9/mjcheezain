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
            background-color: #E85D85;
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

        /* ============ MOBILE APP STYLE (< md) — visual only, all IDs/classes/JS hooks untouched ============ */
        @media (max-width: 767px) {

            /* --- Tabs as horizontally scrollable pills --- */
            #vTabsWrap > div {
                border-bottom: 0 !important;
                overflow-x: auto;
                gap: 8px;
                padding: 2px 2px 8px;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            #vTabsWrap > div::-webkit-scrollbar {
                display: none;
            }
            #vTabsWrap .tab-btn {
                flex-shrink: 0;
                white-space: nowrap;
                padding: 9px 18px !important;
                border-radius: 9999px;
                border: 1px solid rgba(232, 93, 133, 0.18) !important;
                background: #fff;
                color: #6b7280 !important;
                font-size: 13px;
                font-weight: 600;
                box-shadow: 0 1px 6px rgba(232, 93, 133, 0.06);
                transition: all 0.2s ease;
            }
            /* Active pill — JS toggles the border-b-2 class alongside the brand color classes */
            #vTabsWrap .tab-btn.border-b-2 {
                background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
                color: #fff !important;
                border-color: transparent !important;
                box-shadow: 0 4px 12px rgba(255, 125, 160, 0.35);
            }

            /* --- Progress bar labels --- */
            #vProgressWrap .text-sm {
                font-size: 11px;
                font-weight: 600;
            }

            /* --- Tab panels as app cards --- */
            .tab-content {
                background: #fff;
                border-radius: 1.25rem;
                padding: 1.25rem 1rem 1.5rem;
                box-shadow: 0 2px 12px rgba(232, 93, 133, 0.07);
                border: 1px solid rgba(232, 93, 133, 0.08);
            }
            .tab-content > h3 {
                font-size: 1rem !important;
            }

            /* --- Inputs: rounded-xl, 16px (no iOS zoom), pink focus --- */
            .tab-content input[type="text"],
            .tab-content input[type="email"],
            .tab-content input[type="tel"],
            .tab-content select,
            .tab-content textarea {
                font-size: 16px !important;
                border-radius: 0.75rem !important;
                padding: 12px 14px !important;
                border: 1px solid rgba(232, 93, 133, 0.20) !important;
                min-height: 48px;
                -webkit-appearance: none;
                appearance: none;
            }
            .tab-content select {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23E85D85' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 14px center;
                padding-right: 38px !important;
            }
            .tab-content input[type="text"]:focus,
            .tab-content input[type="email"]:focus,
            .tab-content input[type="tel"]:focus,
            .tab-content select:focus,
            .tab-content textarea:focus {
                outline: none !important;
                border-color: #F9A8C5 !important;
                box-shadow: 0 0 0 3px rgba(232, 93, 133, 0.15) !important;
            }

            /* --- Image upload areas as dashed app-cards --- */
            .tab-content input[type="file"] {
                width: 100%;
                padding: 14px !important;
                border: 1.5px dashed rgba(232, 93, 133, 0.40) !important;
                border-radius: 1rem !important;
                background: linear-gradient(115deg, rgba(255, 125, 160, 0.06) 0%, rgba(255, 194, 117, 0.06) 100%);
                font-size: 13px;
            }
            .tab-content input[type="file"]::file-selector-button {
                background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
                color: #fff;
                border: 0;
                border-radius: 9999px;
                padding: 8px 14px;
                font-weight: 600;
                margin-right: 12px;
            }
            .tab-content input[type="file"]::-webkit-file-upload-button {
                background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
                color: #fff;
                border: 0;
                border-radius: 9999px;
                padding: 8px 14px;
                font-weight: 600;
                margin-right: 12px;
            }
            #profile-preview {
                border-radius: 9999px;
                object-fit: cover;
            }
            #logo-preview,
            #banner-preview,
            #cnic-front-preview,
            #cnic-back-preview {
                border-radius: 0.75rem;
            }

            /* --- Save / Back buttons: full-width, stacked (save on top) --- */
            .tab-content > .flex.mt-8 {
                flex-direction: column-reverse;
                gap: 10px;
            }
            .tab-content .btn-next,
            .tab-content #save-address-btn {
                width: 100%;
                padding: 13px 16px !important;
                border-radius: 1rem !important;
                font-weight: 700;
                font-size: 15px;
            }
            .tab-content .btn-back {
                width: 100%;
                padding: 12px 16px !important;
                border-radius: 1rem !important;
                font-weight: 600;
            }

            /* --- Word counter pills --- */
            #word-count,
            #char-count {
                font-size: 10px !important;
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
            :user="$user"
            page='Profile'
        />

        <!-- Main Column -->
        <div class="flex flex-col flex-1 min-w-0" style="background-color: #FFF6F0;">
            <x-vendor.app-header title="Edit Store" back="{{ route('vendor.profile') }}" />

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6 pb-28 md:pb-8 scrollbar-hide min-w-0 page-enter">
                <div class="container mx-auto max-w-4xl md:p-4">

                    <div id="vProgressWrap">
                        <x-vendor.progress-bar />
                    </div>
                    <div id="vTabsWrap">
                        <x-vendor.tab-navigation />
                    </div>
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
    </div>

    <!-- Logout Modal Component -->
    <x-logout-modal />

    <script src="{{ asset('js/vendor_edit-profile.js') }}"></script>
    <script src="{{ asset('js/vendor_navbar.js') }}"></script>
</body>
@endsection
