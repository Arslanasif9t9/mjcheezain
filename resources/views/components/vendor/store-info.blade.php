@props([
    'businessType', 
    'storeCategory', 
    'returnPolicy', 
    'returnPolicyFile', 
    'shippingPolicy', 
    'shippingPolicyFile', 
    'storeDescription', 
    'storeBanner'
])

<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-xl font-semibold mb-4 text-gray-800">Store Info</h3>
    <div class="space-y-4">
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Type:</strong>
            <span class="text-gray-600">{{ $businessType }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Category:</strong>
            <span class="text-gray-600">{{ $storeCategory }}</span>
        </div>
        
        <!-- Policies -->
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Feature Policy:</strong>
            <div class="flex space-x-4">
                @if($returnPolicy || $returnPolicyFile)
                    <a href="{{ $returnPolicyFile ? asset('uploads/' . $returnPolicyFile) : '#' }}" 
                       class="text-blue-600 hover:text-blue-800 underline text-sm transition-colors"
                       {{ $returnPolicyFile ? 'download' : '' }}>
                        Return Policy
                    </a>
                @else
                    <span class="text-gray-500 text-sm">Not available</span>
                @endif
                
                @if($shippingPolicy || $shippingPolicyFile)
                    <a href="{{ $shippingPolicyFile ? asset('uploads/' . $shippingPolicyFile) : '#' }}" 
                       class="text-blue-600 hover:text-blue-800 underline text-sm transition-colors"
                       {{ $shippingPolicyFile ? 'download' : '' }}>
                        Shipping Policy
                    </a>
                @else
                    <span class="text-gray-500 text-sm">Not available</span>
                @endif
            </div>
        </div>
        
        <!-- Description -->
        <div class="py-2">
            <strong class="text-gray-700 block mb-2">Description:</strong>
            <div class="text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-lg">
                {!! nl2br(e($storeDescription)) !!}
            </div>
        </div>
        
        <!-- Store Banner -->
        @if($storeBanner)
            <div class="py-2">
                <strong class="text-gray-700 block mb-2">Banner:</strong>
                <img src="{{ $storeBanner }}" 
                     alt="Store Banner" 
                     class="w-full h-48 object-cover rounded-lg shadow-md">
            </div>
        @endif
    </div>
</div>