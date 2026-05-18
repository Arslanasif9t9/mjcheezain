@props([
    'completionPercentage',
    'activeTab',
    'totalProducts',
    'pendingProducts'
])

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div class="w-1/3">
        <i class="fa-solid fa-magnifying-glass" style="position: relative; right: -10px; top: 32px;"></i> 
        <input type="text" id="productSearch" placeholder="Search products" 
               class="border px-12 py-2 rounded border-0 pl-8 border-0 outline-0 w-full" />
    </div>
    <div>
        <div class="w-48 h-[5px] bg-gray-300" style="border-radius: 20px;">
            <div class="bg-green-500 h-full transition-all duration-300" 
                 style="width: {{ $completionPercentage }}%"></div>
        </div>
        <p class="text-center"><span>{{ $completionPercentage }}%</span> Completed</p>
    </div>
</div>
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Products</h2>
    <div class="flex gap-4 relative">
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="open = !open"
                @click.away="open = false"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition flex items-center gap-2"
            >
                Add Product
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="{ 'rotate-180': open }" style="transition: transform 0.2s">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <!-- Dropdown menu -->
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10"
            >
                <a 
                    href="{{ route('vendor.products.autoparts.create') }}" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    🚗 Auto Parts
                </a>
                <a 
                    href="{{ route('vendor.products.create') }}" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    📦 Other Products
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Tabs -->
<div class="flex justify-between items-center mb-4">
    <div class="flex space-x-8 border-b">
        <button data-tab="online" class="tab-button pb-2 border-b-2 border-blue-500 text-blue-500 transition">
            Online
        </button>
        <button data-tab="pending" class="tab-button pb-2 text-gray-600 transition">
            Pending
        </button>
        <button data-tab="offline" class="tab-button pb-2 text-gray-600 transition">
            Offline
        </button>
        <button data-tab="draft" class="tab-button pb-2 text-gray-600 transition">
            Draft
        </button>
        <button data-tab="all" class="tab-button pb-2 text-gray-600 transition">
            All
        </button>
    </div>
</div>