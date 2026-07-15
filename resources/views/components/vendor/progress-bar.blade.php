@props(['currentStep' => 1])

<div class="mb-8">
    <div class="h-2 bg-gray-300 rounded-full transition-all duration-300">
        <div id="progress-bar" class="h-2 rounded-full transition-all duration-300"
             style="background: linear-gradient(90deg, #FF7DA0, #FFC275); width: {{ $currentStep == 1 ? '33%' : ($currentStep == 2 ? '66%' : '100%') }};"></div>
    </div>
    <div class="flex justify-around mt-2 text-sm">
        <span class="{{ $currentStep >= 1 ? 'text-[#E85D85] font-semibold' : 'text-gray-500' }}">1. Basic Info</span>
        <span class="{{ $currentStep >= 2 ? 'text-[#E85D85] font-semibold' : 'text-gray-500' }}">2. Store Details</span>
        <span class="{{ $currentStep >= 3 ? 'text-[#E85D85] font-semibold' : 'text-gray-500' }}">3. Address</span>
    </div>
</div>