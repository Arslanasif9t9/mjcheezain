@props(['address' => []])

<div class="tab-content {{ $attributes->get('class') }}" id="address">
    <h3 class="text-xl font-bold mb-4">📌 Delivery & Pickup Details</h3>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="pickup-address">Warehouse / Pickup Address</label>
        <textarea id="pickup-address" name="pickup_address" rows="3" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-300 focus:border-pink-300">{{ $address['pickup_address'] ?? '' }}</textarea>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="area">Province</label>
        <select id="area" name="area" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-300 focus:border-pink-300"
            onchange="updateCities()">
            <option value="">Select province</option>
            <option value="Punjab" {{ ($address['area'] ?? '') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
            <option value="Sindh" {{ ($address['area'] ?? '') == 'Sindh' ? 'selected' : '' }}>Sindh</option>
            <option value="Khyber Pakhtunkhwa" {{ ($address['area'] ?? '') == 'Khyber Pakhtunkhwa' ? 'selected' : '' }}>Khyber Pakhtunkhwa</option>
            <option value="Balochistan" {{ ($address['area'] ?? '') == 'Balochistan' ? 'selected' : '' }}>Balochistan</option>
        </select>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="city">City</label>
        <select id="city" name="city" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-300 focus:border-pink-300">
            <option value="">Select City</option>
            <!-- Cities will be populated dynamically based on province selection -->
        </select>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="country">Country</label>
        <input type="text" id="country" name="country" value="Pakistan" readonly
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed">
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="postal-code">Postal Code</label>
        <input type="text" id="postal-code" name="postal_code" value="{{ $address['postal_code'] ?? '' }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-300 focus:border-pink-300">
    </div>

    <div class="flex justify-between mt-8">
        <button type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 btn-back"
            data-prev-tab="store-details">Back</button>
        <button type="button"
            class="px-4 py-2 text-white rounded-full hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:ring-offset-2 flex items-center justify-center min-w-[120px]" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 12px rgba(255, 125, 160, 0.35);" 
            id="save-address-btn">
            <span id="btn-text">Submit</span>
            <div id="btn-loader" class="hidden ml-2">
                <div class="spinner"></div>
            </div>
        </button>
    </div>
</div>

<script>
// City data by province
const cityData = {
    "Punjab": [
        "Lahore",
        "Faisalabad",
        "Rawalpindi",
        "Islamabad",
        "Multan",
        "Gujranwala",
        "Sialkot",
        "Bahawalpur",
        "Sargodha",
        "Sheikhupura",
        "Gujrat",
        "Jhang",
        "Sahiwal",
        "Wah Cantonment",
        "Kasur",
        "Okara",
        "Chiniot",
        "Kamoke",
        "Hafizabad",
        "Mandi Bahauddin",
        "Attock",
        "Jhelum",
        "Rahim Yar Khan"
    ],
    "Sindh": [
        "Karachi",
        "Hyderabad",
        "Sukkur",
        "Larkana",
        "Nawabshah (Shaheed Benazirabad)",
        "Mirpur Khas",
        "Jacobabad",
        "Shikarpur",
        "Khairpur",
        "Dadu",
        "Badin",
        "Thatta",
        "Ghotki",
        "Sanghar",
        "Umerkot",
        "Tando Allahyar",
        "Tando Adam",
        "Kotri",
        "Matiari",
        "Kamber Shahdadkot"
    ],
    "Khyber Pakhtunkhwa": [
        "Peshawar",
        "Mardan",
        "Abbottabad",
        "Swat (Mingora)",
        "Dera Ismail Khan",
        "Charsadda",
        "Nowshera",
        "Kohat",
        "Bannu",
        "Swabi",
        "Haripur",
        "Mansehra",
        "Karak",
        "Hangu",
        "Lakki Marwat",
        "Tank",
        "Batkhela",
        "Chitral",
        "Shangla",
        "Buner"
    ],
    "Balochistan": [
        "Quetta",
        "Gwadar",
        "Khuzdar",
        "Turbat",
        "Chaman",
        "Hub",
        "Dera Murad Jamali",
        "Dera Allah Yar",
        "Usta Mohammad",
        "Loralai",
        "Pasni",
        "Zhob",
        "Kharan",
        "Mastung",
        "Nushki",
        "Kalat",
        "Panjgur",
        "Awaran",
        "Jaffarabad",
        "Sibi"
    ]
};

document.addEventListener("DOMContentLoaded", function () {
    // Initialize cities based on saved province (if any)
    const savedProvince = document.getElementById('area').value;
    const savedCity = "{{ $address['city'] ?? '' }}";
    
    if (savedProvince) {
        updateCities(savedProvince, savedCity);
    }
    
    // Set up save button
    const saveBtn = document.getElementById('save-address-btn');
    if (!saveBtn) return;

    const csrfToken = "{{ csrf_token() }}";

    saveBtn.addEventListener("click", function (e) {
        e.preventDefault();

        // If button is already disabled/loading, don't proceed
        if (saveBtn.disabled) return;

        // Validate required fields
        const province = document.querySelector("#area").value;
        const city = document.querySelector("#city").value;
        const pickupAddress = document.querySelector("#pickup-address").value;
        
        if (!province) {
            showError('Please select a province.');
            document.querySelector("#area").focus();
            return;
        }
        
        if (!city) {
            showError('Please select a city.');
            document.querySelector("#city").focus();
            return;
        }
        
        if (!pickupAddress.trim()) {
            showError('Please enter pickup address.');
            document.querySelector("#pickup-address").focus();
            return;
        }

        // Disable button and show loading
        setButtonLoading(true);

        const formData = new FormData();
        formData.append("pickup_address", pickupAddress);
        formData.append("city", city);
        formData.append("area", province);
        formData.append("country", "Pakistan");
        formData.append("postal_code", document.querySelector("#postal-code")?.value || "");

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
                showSuccess('Address updated successfully! ✅ Redirecting to profile...');
                
                // Keep button in loading state for 1 second before redirect
                setTimeout(() => {
                    location.href = "/vendor/profile";
                }, 1000);
            } else {
                showError(data.message || 'Failed to update address.');
                // Re-enable button on error
                setButtonLoading(false);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showError('Something went wrong while saving address.');
            // Re-enable button on error
            setButtonLoading(false);
        });
    });
});

function updateCities(province = null, savedCity = null) {
    const provinceSelect = document.getElementById('area');
    const citySelect = document.getElementById('city');
    
    // Get selected province
    const selectedProvince = province || provinceSelect.value;
    
    // Clear current options except the first one
    citySelect.innerHTML = '<option value="">Select City</option>';
    
    if (!selectedProvince) {
        citySelect.disabled = true;
        citySelect.classList.add('bg-gray-100');
        return;
    }
    
    // Enable city select
    citySelect.disabled = false;
    citySelect.classList.remove('bg-gray-100');
    
    // Add cities for selected province
    const cities = cityData[selectedProvince] || [];
    
    cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city.toLowerCase().replace(/[^a-z0-9\s]/g, '').replace(/\s+/g, '_');
        option.textContent = city;
        
        // Check if this is the saved city
        if (savedCity && city.toLowerCase() === savedCity.toLowerCase()) {
            option.selected = true;
        } else if (savedCity && option.value === savedCity) {
            option.selected = true;
        }
        
        citySelect.appendChild(option);
    });
    
    // If no saved city and there's data in the address prop, try to match
    if (!savedCity && "{{ $address['city'] ?? '' }}") {
        const propCity = "{{ $address['city'] ?? '' }}";
        const matchingOption = Array.from(citySelect.options).find(opt => 
            opt.value === propCity || opt.textContent.toLowerCase() === propCity.toLowerCase()
        );
        if (matchingOption) {
            matchingOption.selected = true;
        }
    }
}

// Function to set button loading state
function setButtonLoading(isLoading) {
    const saveBtn = document.getElementById('save-address-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoader = document.getElementById('btn-loader');
    
    if (isLoading) {
        // Disable button
        saveBtn.disabled = true;
        saveBtn.classList.add('opacity-75', 'cursor-not-allowed');
        saveBtn.classList.remove('hover:opacity-90');
        
        // Change text and show loader
        btnText.textContent = 'Processing...';
        btnLoader.classList.remove('hidden');
    } else {
        // Enable button
        saveBtn.disabled = false;
        saveBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        saveBtn.classList.add('hover:opacity-90');
        
        // Restore original text and hide loader
        btnText.textContent = 'Submit';
        btnLoader.classList.add('hidden');
    }
}

// Helper functions (should be in your main JS)
function showSuccess(message) {
    // Create success notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-md shadow-lg z-50 flex items-center animate-slide-in';
    toast.innerHTML = `
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        ${message}
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function showError(message) {
    // Create error notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-md shadow-lg z-50 flex items-center animate-slide-in';
    toast.innerHTML = `
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        ${message}
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add animation styles if not already present
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-in {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slide-out {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out forwards;
    }
    
    .animate-slide-out {
        animation: slide-out 0.3s ease-out forwards;
    }
    
    #country:read-only {
        background-color: #f3f4f6;
        cursor: not-allowed;
        color: #6b7280;
    }
    
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    #save-address-btn:disabled {
        opacity: 0.75;
        cursor: not-allowed;
    }
    
    #save-address-btn:disabled:hover {
        background-color: #2563eb !important;
    }
`;
document.head.appendChild(style);
</script>