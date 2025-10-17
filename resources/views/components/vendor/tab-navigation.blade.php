@props(['activeTab' => 'basic-info'])

<div class="flex border-b border-gray-200 mb-5">
    <button class="px-4 py-2 font-medium {{ $activeTab == 'basic-info' ? 'text-green-600 border-b-2 border-green-500' : 'text-gray-500' }} tab-btn"
            data-tab="basic-info">Basic Info</button>
    <button class="px-4 py-2 font-medium {{ $activeTab == 'store-details' ? 'text-green-600 border-b-2 border-green-500' : 'text-gray-500' }} tab-btn"
            data-tab="store-details">Store Details</button>
    <button class="px-4 py-2 font-medium {{ $activeTab == 'address' ? 'text-green-600 border-b-2 border-green-500' : 'text-gray-500' }} tab-btn"
            data-tab="address">Address</button>
</div>