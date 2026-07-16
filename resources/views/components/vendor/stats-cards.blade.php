@props(['totalProducts', 'totalSales', 'newOrders'])

<div class="grid grid-cols-3 gap-3 md:gap-6 mb-6">
    <a href="{{ route('vendor.products') }}" class="group block">
        <div class="app-card p-3.5 md:p-6 text-center md:text-left h-full md:flex md:items-center md:justify-between hover:shadow-md md:hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-10 h-10 md:w-14 md:h-14 mx-auto md:mx-0 rounded-2xl md:rounded-full bg-gradient-to-br from-emerald-400 to-teal-400 text-white flex items-center justify-center shadow-lg shadow-emerald-200 md:order-2 group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-box-open text-sm md:text-2xl"></i>
            </div>
            <div class="mt-2 md:mt-0 md:order-1">
                <p class="hidden md:block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">All Products</p>
                <p class="text-xl md:text-3xl font-extrabold text-gray-800 leading-none">{{ $totalProducts }}</p>
                <p class="md:hidden text-[10px] text-gray-500 font-medium mt-1">Products</p>
            </div>
        </div>
    </a>
    <a href="{{ route('vendor.products') }}" class="group block">
        <div class="app-card p-3.5 md:p-6 text-center md:text-left h-full md:flex md:items-center md:justify-between hover:shadow-md md:hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-10 h-10 md:w-14 md:h-14 mx-auto md:mx-0 rounded-2xl md:rounded-full brand-gradient text-white flex items-center justify-center brand-shadow md:order-2 group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-boxes-packing text-sm md:text-2xl"></i>
            </div>
            <div class="mt-2 md:mt-0 md:order-1">
                <p class="hidden md:block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Sales</p>
                <p class="text-xl md:text-3xl font-extrabold text-gray-800 leading-none">{{ $totalSales }}</p>
                <p class="md:hidden text-[10px] text-gray-500 font-medium mt-1">Sales</p>
            </div>
        </div>
    </a>
    <a href="{{ route('vendor.orders') }}" class="group block">
        <div class="app-card p-3.5 md:p-6 text-center md:text-left h-full md:flex md:items-center md:justify-between hover:shadow-md md:hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-10 h-10 md:w-14 md:h-14 mx-auto md:mx-0 rounded-2xl md:rounded-full bg-gradient-to-br from-amber-400 to-orange-400 text-white flex items-center justify-center shadow-lg shadow-orange-200 md:order-2 group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-cart-plus text-sm md:text-2xl"></i>
            </div>
            <div class="mt-2 md:mt-0 md:order-1">
                <p class="hidden md:block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">New Orders</p>
                <p class="text-xl md:text-3xl font-extrabold text-gray-800 leading-none">{{ $newOrders }}</p>
                <p class="md:hidden text-[10px] text-gray-500 font-medium mt-1">New Orders</p>
            </div>
        </div>
    </a>
</div>
