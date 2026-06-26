@props(['storeLogo', 'storeName', 'rating', 'verified', 'city', 'country', 'storeBanner'])

<div class="profile-header p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-center relative overflow-hidden min-h-[220px]">
    <!-- Background Banner -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('storage/vendor/store/' . $storeBanner) ?: asset('img/default-banner.jpg') }}" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-65"></div>
    </div>
    
    <div class="flex flex-col sm:flex-row items-center text-center sm:text-left space-y-4 sm:space-y-0 sm:space-x-6 relative z-10 w-full max-w-2xl justify-center sm:justify-start">
        <!-- Store Logo -->
        <div class="bg-green-400 p-0.5 rounded-full flex-shrink-0">
            <img src="{{ asset('storage/vendor/store/' . $storeLogo) }}" 
                 alt="Store Logo" 
                 class="rounded-full w-24 h-24 object-cover border-4 border-white shadow-md">
        </div>
        
        <!-- Store Info -->
        <div class="text-white flex-1">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $storeName }}</h1>
            
            <!-- Rating -->
            <div class="flex items-center space-x-2 mt-2 justify-center sm:justify-start">
                <div class="flex space-x-1">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($rating))
                            <i class="fas fa-star text-yellow-400"></i>
                        @elseif($i - 0.5 <= $rating)
                            <i class="fas fa-star-half-alt text-yellow-400"></i>
                        @else
                            <i class="far fa-star text-yellow-400"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-sm text-gray-200">({{ number_format($rating, 1) }})</span>
            </div>
            
            <!-- Verified Badge -->
            @if($verified)
                <span class="verified-badge bg-green-500 text-white text-xs px-3 py-1 rounded-full font-medium mt-2 inline-block">
                    ✓ Verified
                </span>
            @endif
            
            <!-- Location -->
            <p class="text-gray-200 text-sm mt-2">{{ $city }}, {{ $country }}</p>
            
            <!-- Edit Profile Button -->
            <a href="{{ route('vendor.profile.edit') }}" 
               class="inline-block mt-4 bg-red-500 hover:bg-red-600 active:bg-red-700 text-white px-5 py-2 rounded-xl transition-all font-semibold shadow-sm hover:shadow text-sm">
                Edit Profile
            </a>
        </div>
    </div>
</div>