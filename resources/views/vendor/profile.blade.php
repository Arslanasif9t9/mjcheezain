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
            :user="$user"
            page='Profile'
        />

        <!-- Main Column -->
        <div class="flex flex-col flex-1 min-w-0" style="background-color: #FFF6F0;">
            <x-vendor.app-header title="My Store" subtitle="Store profile & settings" />

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6 pb-28 md:pb-8 overflow-y-visible scrollbar-hide min-w-0 page-enter">

                {{-- ============ MOBILE APP VIEW (< md) ============ --}}
                <div class="md:hidden space-y-4">
                    @php
                        $mIsActive = (bool) ($vendorBasicInfo->profile_visibility ?? 1);
                        $mBanner = \Illuminate\Support\Str::startsWith($store_banner, ['http://', 'https://', '/'])
                            ? $store_banner
                            : asset('storage/vendor/store/' . $store_banner);
                        $mVendorId = $user->user_id;
                        $mProducts = DB::table('vendor_products')->where('user_id', $mVendorId)->count();
                        $mOrders = DB::table('orders')->where('vendor_id', $mVendorId)->count();
                        $mDelivered = DB::table('orders')->where('vendor_id', $mVendorId)->where('fulfillment', 'delivered')->count();
                        $mPending = max($mOrders - $mDelivered, 0);
                    @endphp

                    {{-- Store hero card --}}
                    <div class="app-card overflow-hidden">
                        <div class="h-28 brand-gradient relative">
                            <img src="{{ $mBanner }}" alt="Store banner"
                                 class="absolute inset-0 w-full h-full object-cover"
                                 onerror="this.style.display='none'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/25 to-transparent pointer-events-none"></div>
                        </div>
                        <div class="px-4 pb-4 -mt-10 relative">
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/vendor/store/' . $store_logo) }}" alt="Store logo"
                                     class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-lg bg-white">
                                <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full border-2 border-white {{ $mIsActive ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            </div>
                            <div class="mt-2.5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="text-lg font-extrabold text-gray-900 leading-tight">{{ $store_name }}</h2>
                                    <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold {{ $mIsActive ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                                        {{ $mIsActive ? 'Active' : 'Closed' }}
                                    </span>
                                    @if ($verified)
                                        <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-blue-100 text-blue-600">
                                            <i class="fas fa-check mr-1"></i>Verified
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5 mt-1.5 text-[12px] text-gray-500 font-medium min-w-0">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span class="font-bold text-gray-700">{{ number_format($rating, 1) }}</span>
                                    <span class="text-gray-300">&bull;</span>
                                    <i class="fas fa-map-marker-alt text-[#E85D85]"></i>
                                    <span class="truncate">{{ $city }}, {{ $country }}</span>
                                </div>
                            </div>
                            <a href="{{ route('vendor.profile.edit') }}"
                               class="mt-4 w-full flex items-center justify-center gap-2 py-3 brand-gradient brand-shadow text-white rounded-2xl text-sm font-bold active:scale-95 transition-transform">
                                <i class="fas fa-user-edit"></i> Edit Profile
                            </a>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="app-card p-3.5 text-center">
                            <div class="text-xl font-extrabold text-[#E85D85] mb-0.5">{{ $mProducts }}</div>
                            <p class="text-[11px] text-gray-500 font-medium">Products</p>
                        </div>
                        <div class="app-card p-3.5 text-center">
                            <div class="text-xl font-extrabold text-amber-500 mb-0.5">{{ $mOrders }}</div>
                            <p class="text-[11px] text-gray-500 font-medium">Total Orders</p>
                        </div>
                        <div class="app-card p-3.5 text-center">
                            <div class="text-xl font-extrabold text-emerald-500 mb-0.5">{{ $mDelivered }}</div>
                            <p class="text-[11px] text-gray-500 font-medium">Delivered</p>
                        </div>
                        <div class="app-card p-3.5 text-center">
                            <div class="text-xl font-extrabold text-purple-500 mb-0.5">{{ $mPending }}</div>
                            <p class="text-[11px] text-gray-500 font-medium">In Progress</p>
                        </div>
                    </div>

                    {{-- Personal info --}}
                    <div class="app-card overflow-hidden">
                        <div class="px-4 pt-4 pb-1.5">
                            <h3 class="text-sm font-bold text-gray-900">Personal Info</h3>
                        </div>
                        <div>
                            <div class="flex items-start gap-3 px-4 py-3 border-b border-pink-50">
                                <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-gray-400 font-medium">Full Name</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $full_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 px-4 py-3 border-b border-pink-50">
                                <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-gray-400 font-medium">Email</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $user_email }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 px-4 py-3 pb-4">
                                <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone-alt text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-gray-400 font-medium">Mobile No</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $phone ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Store info --}}
                    <div class="app-card overflow-hidden">
                        <div class="px-4 pt-4 pb-1.5">
                            <h3 class="text-sm font-bold text-gray-900">Store Info</h3>
                        </div>
                        <div>
                            <div class="flex items-start gap-3 px-4 py-3 border-b border-pink-50">
                                <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-briefcase text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-gray-400 font-medium">Business Type</p>
                                    <p class="text-sm font-semibold text-gray-800 capitalize break-words">{{ $business_type }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 px-4 py-3 border-b border-pink-50">
                                <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-tags text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-gray-400 font-medium">Category</p>
                                    <p class="text-sm font-semibold text-gray-800 capitalize break-words">{{ $store_category }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 px-4 py-3 pb-4">
                                <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-align-left text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-gray-400 font-medium">Description</p>
                                    <p class="text-sm text-gray-700 leading-relaxed break-words">{!! nl2br(e($store_description)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="app-card overflow-hidden">
                        <div class="px-4 pt-4 pb-1.5">
                            <h3 class="text-sm font-bold text-gray-900">Address</h3>
                        </div>
                        <div>
                            <div class="flex items-start gap-3 px-4 py-3 border-b border-pink-50">
                                <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-warehouse text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-gray-400 font-medium">Warehouse / Pickup Address</p>
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $pickup_address }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 border-b border-pink-50">
                                <div class="flex items-start gap-3 px-4 py-3 border-r border-pink-50">
                                    <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-city text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[11px] text-gray-400 font-medium">City</p>
                                        <p class="text-sm font-semibold text-gray-800 capitalize break-words">{{ $city }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 px-4 py-3">
                                    <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[11px] text-gray-400 font-medium">Area / Region</p>
                                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $area }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="flex items-start gap-3 px-4 py-3 pb-4 border-r border-pink-50">
                                    <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-flag text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[11px] text-gray-400 font-medium">Country</p>
                                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $country }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 px-4 py-3 pb-4">
                                    <div class="w-9 h-9 rounded-xl brand-gradient-soft text-[#E85D85] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-mail-bulk text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[11px] text-gray-400 font-medium">Postal Code</p>
                                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $postal_code }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ DESKTOP VIEW (md+) — unchanged ============ --}}
                <div class="hidden md:block">
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
                </div>
            </main>
        </div>
    </div>

    <!-- Logout Modal Component -->
    <x-logout-modal />
</body>
@endsection
