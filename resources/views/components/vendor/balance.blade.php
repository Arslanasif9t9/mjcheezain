@props(['balance'])

{{-- Mobile: premium gradient wallet card (app style) --}}
<div class="md:hidden order-first brand-gradient rounded-3xl p-5 relative overflow-hidden brand-shadow">
    <div class="absolute -top-8 -right-8 w-28 h-28 bg-white/10 rounded-full pointer-events-none"></div>
    <div class="absolute bottom-0 -left-6 w-20 h-20 bg-white/10 rounded-full pointer-events-none"></div>

    <div class="relative z-10">
        <div class="flex items-center justify-between">
            <p class="text-[11px] font-semibold text-white/85 uppercase tracking-wider">Current Balance</p>
            <span class="w-9 h-9 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white">
                <i class="fas fa-coins text-sm"></i>
            </span>
        </div>
        <p class="mt-2 text-3xl font-extrabold text-white tracking-tight leading-none">
            Rs. {{ number_format($balance, 2) }}
        </p>
        <a href="{{ route('vendor.withdraw') }}"
           class="mt-4 inline-flex items-center justify-center w-full px-5 py-2.5 bg-white text-[#E85D85] text-sm font-bold rounded-full shadow-md active:scale-[0.98] transition-transform">
            <i class="fas fa-wallet mr-2"></i> Withdraw Now
        </a>
    </div>
</div>

{{-- Desktop: original balance card --}}
<div class="hidden md:flex bg-white p-6 rounded-xl shadow-sm border border-gray-100 justify-center items-center relative overflow-hidden group hover:shadow-md transition-all duration-300">
    <!-- Gradient accent decoration -->
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#FF7DA0] to-[#FFC275]"></div>

    <div class="text-center w-full">
        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Current Balance</p>
        <div class="my-4">
            <span class="text-4xl font-extrabold text-gray-800 tracking-tight">
                {{ number_format($balance, 2) }}
            </span>
            <span class="text-sm font-bold text-gray-500 ml-1">PKR</span>
        </div>

        <div class="w-2/3 h-px bg-gray-100 mx-auto my-5"></div>

        <a href="{{ route('vendor.withdraw') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-white font-semibold rounded-full hover:opacity-90 hover:shadow-md transition-all duration-200 w-full md:w-auto" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 14px rgba(255, 125, 160, 0.35);">
            <i class="fas fa-wallet mr-2"></i> Withdraw Now
        </a>
    </div>
</div>
