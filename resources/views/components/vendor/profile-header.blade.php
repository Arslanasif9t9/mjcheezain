@props(['storeLogo', 'storeName', 'rating', 'verified', 'city', 'country', 'storeBanner'])

<div class="profile-header p-6 rounded-lg shadow flex items-center justify-center relative overflow-hidden">
    <!-- Background Banner -->
    <div class="absolute inset-0 z-0">
        <img src="{{ $storeBanner ?: asset('img/default-banner.jpg') }}" 
             alt="Store Banner" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    </div>
    
    <div class="flex items-center space-x-6 relative z-10">
        <!-- Store Logo -->
        <div class="bg-green-400 p-1 rounded-full">
            <img src="{{ $storeLogo }}" 
                 alt="Store Logo" 
                 class="rounded-full w-20 h-20 object-cover border-4 border-white">
        </div>
        
        <!-- Store Info -->
        <div class="text-white">
            <h1 class="text-3xl font-bold">{{ $storeName }}</h1>
            
            <!-- Rating -->
            <div class="flex items-center space-x-2 mt-2">
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
               class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Edit Profile
            </a>
        </div>
    </div>
</div>