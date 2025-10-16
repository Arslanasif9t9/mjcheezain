@props(['totalProducts', 'totalSales', 'newOrders'])

<div class="v-stats grid grid-cols-1 md:grid-cols-3 gap-4 text-center mb-6">
    <a href="./products.php">
        <div class="bg-white p-4 rounded shadow">
            <i class="fa-solid fa-box-open text-green-900 text-4xl row-span-2"></i>
            <p class="text-2xl font-bold text-black">{{ $totalProducts }}</p>
            <p class="text-sm text-gray-500">All Product</p>
        </div>
    </a>
    <a href="./products.php">
        <div class="bg-white p-4 rounded shadow">
            <i class="fa-solid fa-boxes-packing text-blue-400 text-4xl row-span-2"></i>
            <p class="text-lg font-bold">{{ $totalSales }}</p>
            <p class="text-sm text-gray-500">Total Sales</p>
        </div>
    </a>
    <a href="./orders.php">
        <div class="bg-white p-4 rounded shadow">
            <i class="fa-solid fa-cart-plus text-blue-900 text-4xl row-span-2"></i>
            <p class="text-lg font-bold">{{ $newOrders }}</p>
            <p class="text-sm text-gray-500">New Order</p>
        </div>
    </a>
</div>