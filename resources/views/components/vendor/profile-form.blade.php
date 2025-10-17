@props([
    'basicInfo' => [],
    'storeDetails' => [],
    'address' => [],
    'currentStep' => 1,
    'activeTab' => 'basic-info'
])

{{-- <form id="vendor-profile-form" class="bg-white p-6 rounded-lg shadow-md" action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data"> --}}
    {{-- @csrf --}}
    
    {{-- <x-progress-bar :current-step="$currentStep" /> --}}
    {{-- <x-tab-navigation :active-tab="$activeTab" /> --}}
    {{-- {{dd($basicInfo)}} --}}

    <x-vendor.basic-info-tab 
        :basicInfo="$basicInfo" 
        class="{{ $activeTab == 'basic-info' ? 'active' : 'hidden' }}" 
    />
    
    <x-vendor.store-detail-tab 
        :storeDetails="$storeDetails" 
        class="{{ $activeTab == 'store-details' ? 'active' : 'hidden' }}" 
    />
    
    <x-vendor.address-tab 
        :address="$address" 
        class="{{ $activeTab == 'address' ? 'active' : 'hidden' }}" 
    />
{{-- </form> --}}