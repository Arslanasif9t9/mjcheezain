@props(['address' => []])

<div class="tab-content {{ $attributes->get('class') }}" id="address">
    <h3 class="text-xl font-bold mb-4">📌 Delivery & Pickup Details</h3>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="pickup-address">Warehouse / Pickup Address</label>
        <textarea id="pickup-address" name="pickup_address" rows="3" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">{{ $address['pickup_address'] ?? '' }}</textarea>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="city">City</label>
        <select id="city" name="city" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
            <option value="">Select City</option>
            <option value="karachi" {{ ($address['city'] ?? '') == 'karachi' ? 'selected' : '' }}>Karachi</option>
            <option value="lahore" {{ ($address['city'] ?? '') == 'lahore' ? 'selected' : '' }}>Lahore</option>
            <option value="islamabad" {{ ($address['city'] ?? '') == 'islamabad' ? 'selected' : '' }}>Islamabad</option>
        </select>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="area">Province</label>
        <select id="area" name="area" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
            <option value="">Select province</option>
            <option value="Punjab" {{ ($address['area'] ?? '') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
            <option value="Sindh" {{ ($address['area'] ?? '') == 'Sindh' ? 'selected' : '' }}>Sindh</option>
            <option value="Khyber Pakhtunkhwa" {{ ($address['area'] ?? '') == 'Khyber Pakhtunkhwa' ? 'selected' : '' }}>Khyber Pakhtunkhwa</option>
            <option value="Balochistan" {{ ($address['area'] ?? '') == 'Balochistan' ? 'selected' : '' }}>Balochistan</option>
        </select>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="country">Country</label>
        <input type="text" id="country" value="Pakistan" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="postal-code">Postal Code</label>
        <input type="text" id="postal-code" name="postal_code" value="{{ $address['postal_code'] ?? '' }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
    </div>

    <div class="flex justify-between mt-8">
        <button type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 btn-back"
            data-prev-tab="store-details">Back</button>
        <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Submit</button>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const saveBtn = document.querySelector(".tab-content#address button[type='submit']");
    if (!saveBtn) return;

    const csrfToken = "{{ csrf_token() }}";

    saveBtn.addEventListener("click", function (e) {
        e.preventDefault(); // Stop normal form submit

        const formData = new FormData();
        formData.append("pickup_address", document.querySelector("#pickup-address")?.value || "");
        formData.append("city", document.querySelector("#city")?.value || "");
        formData.append("area", document.querySelector("#area")?.value || "");
        formData.append("country", document.querySelector("#country")?.value || "");
        formData.append("postal_code", document.querySelector("#postal-code")?.value || "");
        
//         console.log(document.querySelector("#area")?.value)
//         // Check if data exists by iterating
// for (const [key, value] of formData.entries()) {
//     console.log(key, value); // This WILL show: "a" "b"
// }

        fetch("{{ route('vendor.address.update') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                showSuccess('Address updated successfully! ✅');
                setInterval(() => {
                    location.href = "/vendor/profile";
                }, 1000);
            } else {
                showError(data.message || 'Failed to update address.');
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showError('Something went wrong while saving address.');
        });
    });
});
</script>
