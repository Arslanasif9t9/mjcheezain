@props(['activeTab' => 'basic-info'])

<div class="flex border-b border-gray-200 mb-5">
    <button class="px-4 py-2 font-medium {{ $activeTab == 'basic-info' ? 'text-[#E85D85] border-b-2 border-[#E85D85]' : 'text-gray-500' }} tab-btn"
            data-tab="basic-info">Basic Info</button>
    <button class="px-4 py-2 font-medium {{ $activeTab == 'store-details' ? 'text-[#E85D85] border-b-2 border-[#E85D85]' : 'text-gray-500' }} tab-btn"
            data-tab="store-details">Store Details</button>
    <button class="px-4 py-2 font-medium {{ $activeTab == 'address' ? 'text-[#E85D85] border-b-2 border-[#E85D85]' : 'text-gray-500' }} tab-btn"
            data-tab="address">Address</button>
</div>