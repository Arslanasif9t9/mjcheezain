@props(['fullName', 'email', 'phone', 'address'])

<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-xl font-semibold mb-4 text-gray-800">Person Info</h3>
    <div class="space-y-3">
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Full name:</strong>
            <span class="text-gray-600">{{ $fullName }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Email:</strong>
            <span class="text-gray-600">{{ $email }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <strong class="text-gray-700">Mobile no:</strong>
            <span class="text-gray-600">{{ $phone }}</span>
        </div>
        <div class="flex justify-between items-start py-2 border-b border-gray-100">
            <strong class="text-gray-700">Address:</strong>
            <span class="text-gray-600 text-right">{{ $address }}</span>
        </div>
    </div>
</div>