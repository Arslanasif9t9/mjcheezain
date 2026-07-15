@props([
    'completionPercentage',
    'activeTab',
    'totalProducts',
    'pendingProducts'
])

<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center mb-6 gap-4">
    <div class="relative w-full sm:w-1/3">
        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
        </span>
        <input type="text" id="productSearch" placeholder="Search products..." 
               class="border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 w-full bg-white text-sm shadow-sm transition-all" />
    </div>
    <div class="w-full sm:w-auto flex flex-col items-start sm:items-end bg-white p-3 rounded-xl border border-gray-100 shadow-sm sm:bg-transparent sm:border-0 sm:p-0">
        <span class="text-xs font-semibold text-gray-500 mb-1">Product Setup Progress</span>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="w-full sm:w-48 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-300" style="background: linear-gradient(90deg, #FF7DA0, #FFC275); width: {{ $completionPercentage }}%"></div>
            </div>
            <p class="text-xs font-bold text-[#E85D85] whitespace-nowrap"><span>{{ $completionPercentage }}%</span> Done</p>
        </div>
    </div>
</div>

<div class="flex justify-between items-center mb-6 gap-4">
    <h2 class="text-xl md:text-2xl font-extrabold text-gray-800 tracking-tight">Products</h2>
    <div class="flex gap-4 relative">
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="open = !open"
                @click.away="open = false"
                class="text-white px-4 py-2.5 rounded-full hover:opacity-90 transition flex items-center gap-2 font-bold text-sm hover:shadow-md" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 14px rgba(255, 125, 160, 0.35);"
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
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 z-10 border border-gray-100 overflow-hidden"
            >
                <a 
                    href="{{ route('vendor.products.autoparts.create') }}" 
                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-55 hover:text-[#E85D85] font-medium border-b border-gray-50 transition-colors"
                >
                    🚗 Auto Parts
                </a>
                <a 
                    href="{{ route('vendor.products.create') }}" 
                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-55 hover:text-[#E85D85] font-medium transition-colors"
                >
                    📦 Other Products
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="flex items-center mb-6 border-b border-gray-200 overflow-x-auto scrollbar-hide w-full">
    <div class="flex space-x-6 sm:space-x-8 whitespace-nowrap pb-1">
        <button data-tab="online" class="tab-button pb-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">
            Online
        </button>
        <button data-tab="pending" class="tab-button pb-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">
            Pending
        </button>
        <button data-tab="offline" class="tab-button pb-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">
            Offline
        </button>
        <button data-tab="draft" class="tab-button pb-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">
            Draft
        </button>
        <button data-tab="all" class="tab-button pb-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">
            All
        </button>
    </div>
</div>