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
    <div class="flex gap-4">
        <a href="{{ route('vendor.products.create') }}" 
           class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
            Add New Product
        </a>
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