@props(['balance'])

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex justify-center items-center relative overflow-hidden group hover:shadow-md transition-all duration-300">
    <!-- Gradient accent decoration -->
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-green-400 to-emerald-500"></div>
    
    <div class="text-center w-full">
        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Current Balance</p>
        <div class="my-4">
            <span class="text-4xl font-extrabold text-gray-800 tracking-tight">
                {{ number_format($balance, 2) }}
            </span>
            <span class="text-sm font-bold text-gray-500 ml-1">PKR</span>
        </div>
        
        <div class="w-2/3 h-px bg-gray-100 mx-auto my-5"></div>
        
        <a href="{{ route('vendor.withdraw') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200 w-full md:w-auto">
            <i class="fas fa-wallet mr-2"></i> Withdraw Now
        </a>
    </div>
</div>