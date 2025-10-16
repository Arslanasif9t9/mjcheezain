@props(['profilePicture', 'fullName', 'profile_visibility'])

<aside id="aside" class="w-64 bg-gray-900 text-white p-4">
    <div class="flex flex-col items-center">
        <img class="w-24 h-24 rounded-full object-cover" src="{{ $profilePicture }}" alt="Profile" />
        <h2 class="mt-4 font-semibold text-xl">{{ $fullName }}</h2>
        @if($profile_visibility)
            <span class='active-button mt-1 bg-green-500 px-2 rounded-full'>Active</span>
        @else
            <span class='active-button mt-1 bg-red-500 px-2 rounded-full'>Close</span>
        @endif
        <div class="text-yellow-500 mb-4 text-lg"> ★★★★★ </div>
    </div>
    <nav class="space-y-4">
        <a href="./dashboard.php" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded">
            <i class="fa fa-chart-bar"></i> Dashboard
        </a>
        <a href="./products.php" class="flex items-center gap-2">
            <i class="fa fa-box"></i> Products
        </a>
        <a href="./orders.php" class="flex items-center gap-2">
            <i class="fa fa-shopping-cart"></i> Orders
        </a>
        <a href="./withdraw.php" class="flex items-center gap-2">
            <i class="fa fa-wallet"></i> Withdraw
        </a>
        <a href="./profile.php" class="flex items-center gap-2">
            <i class="fa-solid fa-user"></i> Profile
        </a>
        <a href="#" id="logoutBtn" class="flex items-center gap-2">
            <i class="fas fa-sign-out-alt"></i> Log out
        </a>
    </nav>
</aside>