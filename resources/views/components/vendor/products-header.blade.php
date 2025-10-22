@props([
    'completionPercentage',
    'activeTab',
    'totalProducts',
    'pendingProducts'
])

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div class="w-1/3">
        <i class="fa-solid fa-magnifying-glass" style="position: relative; right: -28px;"></i> 
        <input type="text" id="productSearch" placeholder="Search products" 
               class="border px-4 py-2 rounded border-0 pl-8 border-0 outline-0 w-full" />
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
        <a href="{{ route('vendor.products.index', ['tab' => 'online']) }}" 
           class="pb-2 {{ $activeTab === 'online' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-600' }} transition">
            Online
        </a>
        <a href="{{ route('vendor.products.index', ['tab' => 'pending']) }}" 
           class="pb-2 {{ $activeTab === 'pending' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-600' }} transition">
            Pending Review
        </a>
        <a href="{{ route('vendor.products.index', ['tab' => 'offline']) }}" 
           class="pb-2 {{ $activeTab === 'offline' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-600' }} transition">
            Offline
        </a>
        <a href="{{ route('vendor.products.index', ['tab' => 'draft']) }}" 
           class="pb-2 {{ $activeTab === 'draft' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-600' }} transition">
            Draft
        </a>
        <a href="{{ route('vendor.products.index', ['tab' => 'all']) }}" 
           class="pb-2 {{ $activeTab === 'all' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-600' }} transition">
            All
        </a>
    </div>
</div>