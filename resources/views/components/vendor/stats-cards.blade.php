@props(['totalProducts', 'totalSales', 'newOrders'])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <a href="{{ route('vendor.products') }}" class="group block">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
            <div class="text-left">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">All Products</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalProducts }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-box-open text-2xl"></i>
            </div>
        </div>
    </a>
    <a href="{{ route('vendor.products') }}" class="group block">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
            <div class="text-left">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Sales</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalSales }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-pink-50 text-[#E85D85] flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-boxes-packing text-2xl"></i>
            </div>
        </div>
    </a>
    <a href="{{ route('vendor.orders') }}" class="group block">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
            <div class="text-left">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">New Orders</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $newOrders }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-cart-plus text-2xl"></i>
            </div>
        </div>
    </a>
</div>