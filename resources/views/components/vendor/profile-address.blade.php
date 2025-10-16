@props(['pickupAddress', 'city', 'area', 'country', 'postalCode'])

<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-xl font-semibold mb-4 text-gray-800">Address</h3>
    <div class="space-y-3">
        <div class="flex justify-between items-start py-2 border-b border-gray-100">
            <strong class="text-gray-700">Warehouse / Pickup Address:</strong>
            <span class="text-gray-600 text-right">{{ $pickupAddress }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">City:</strong>
            <span class="text-gray-600">{{ $city }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Area / Region:</strong>
            <span class="text-gray-600">{{ $area }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Country:</strong>
            <span class="text-gray-600">{{ $country }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Postal Code:</strong>
            <span class="text-gray-600">{{ $postalCode }}</span>
        </div>
    </div>
</div>