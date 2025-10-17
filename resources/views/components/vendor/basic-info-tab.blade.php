@props(['basicInfo' => []])

<div class="tab-content {{ $attributes->get('class') }}" id="basic-info">
    <h3 class="text-xl font-bold mb-4">📌 Vendor Personal & Account Identity Proof</h3>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="profile-picture">Profile Picture</label>
        <div class="mt-1">
            <input type="file" name="profile_picture" id="profile-picture" accept="image/*" class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-md file:border-0
            file:text-sm file:font-semibold
            file:bg-green-100 file:text-green-700
            hover:file:bg-green-100">
            <div class="mt-2 text-center">
                <img id="profile-preview" src="" alt="Profile Preview" class="mx-auto" style="display: none; width: 120px !important;">
                <span class="text-gray-500 text-sm">No image selected</span>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="full-name">Full Name</label>
        <input type="text" id="full-name" name="full_name" value="{{ $basicInfo['full_name'] ?? '' }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="store-name">Store Name</label>
        <input type="text" id="store-name" name="store_name" value="{{ $basicInfo['store_name'] ?? '' }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="email">Email Address</label>
        <div class="relative">
            <input type="email" id="email" value="{{ $basicInfo['email'] ?? '' }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 bg-gray-100" readonly>
        </div>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" value="{{ $basicInfo['phone'] ?? '' }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
    </div>

    <div class="mb-5 flex items-center">
        <label class="block text-gray-700 font-bold">Profile Visibility (Store Active)</label>
        <label class="switch ml-3">
            <input type="checkbox" id="profile-visibility" name="profile_visibility" {{ ($basicInfo['profile_visibility'] ?? false) ? 'checked' : '' }}>
            <span class="slider"></span>
        </label>
    </div>

    <div class="flex justify-end mt-8">
        <button type="button"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 btn-next"
            data-next-tab="store-details">Save & Continue</button>
    </div>
</div>