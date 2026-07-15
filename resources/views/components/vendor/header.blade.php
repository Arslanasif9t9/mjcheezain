@props(['profilePicture', 'title' => 'Overview'])

<div class="header flex justify-between items-center mb-6 pl-14 md:pl-0 gap-4">
    <h1 class="text-xl md:text-3xl font-extrabold text-gray-800 tracking-tight whitespace-nowrap">{{ $title }}</h1>

    <!-- Desktop Search -->
    <div class="hidden md:flex items-center w-[50%] lg:w-[60%] relative">
        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
        </span>
        <input type="text" placeholder="Search products, orders or customer..."
            class="px-4 py-2.5 pl-11 border border-pink-100 outline-none w-full rounded-full bg-white text-sm focus:ring-2 focus:ring-pink-300 focus:border-pink-300 transition-all shadow-sm" />
    </div>

    <!-- User Section -->
    <div class="flex items-center gap-3">
        <a href="{{ route('vendor.profile') }}" class="group flex items-center gap-2">
            <div class="relative w-10 h-10 rounded-full overflow-hidden ring-2 ring-pink-200 group-hover:ring-[#E85D85] transition-all shadow-sm">
                <img src="{{ asset('storage/vendor/profile/' . ($profilePicture ?? 'default_profile.webp')) }}"
                     alt="Profile"
                     class="w-full h-full object-cover">
            </div>
        </a>
    </div>
</div>
