@props(['storeDetails' => []])

<div class="tab-content {{ $attributes->get('class') }}" id="store-details">
    <h3 class="text-xl font-bold mb-4">📌 Store Commercial Identity & Policies</h3>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="business-type">Business Type</label>
        <select id="business-type" name="business_type" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
            <option value="">Select Business Type</option>
            <option value="individual" {{ ($storeDetails['business_type'] ?? '') == 'individual' ? 'selected' : '' }}>Individual</option>
            <option value="company" {{ ($storeDetails['business_type'] ?? '') == 'company' ? 'selected' : '' }}>Company</option>
        </select>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="store-category">Store Category</label>
        <select id="store-category" name="store_category" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
            <option value="">Select Category</option>
            <option value="electronics" {{ ($storeDetails['store_category'] ?? '') == 'electronics' ? 'selected' : '' }}>Electronics</option>
            <option value="fashion" {{ ($storeDetails['store_category'] ?? '') == 'fashion' ? 'selected' : '' }}>Fashion</option>
            <option value="home" {{ ($storeDetails['store_category'] ?? '') == 'home' ? 'selected' : '' }}>Home & Living</option>
            <option value="beauty" {{ ($storeDetails['store_category'] ?? '') == 'beauty' ? 'selected' : '' }}>Beauty</option>
            <option value="food" {{ ($storeDetails['store_category'] ?? '') == 'food' ? 'selected' : '' }}>Food</option>
        </select>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="store-description">Store Description</label>
        <textarea id="store-description" name="store_description" rows="5" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">{{ $storeDetails['store_description'] ?? '' }}</textarea>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="store-logo">Store Logo</label>
        <div class="mt-1">
            <input type="file" id="store-logo" name="store_logo" accept="image/*" class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-md file:border-0
            file:text-sm file:font-semibold
            file:bg-green-100 file:text-green-700
            hover:file:bg-green-100">
            <div class="mt-2 text-center">
                <img id="logo-preview" src="" alt="Logo Preview" class="mx-auto" style="width: 120px !important; display: none;">
                <span class="text-gray-500 text-sm">No image selected</span>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="store-banner">Store Banner Image</label>
        <div class="mt-1">
            <input type="file" id="store-banner" name="store_banner" accept="image/*" class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-md file:border-0
            file:text-sm file:font-semibold
            file:bg-green-100 file:text-green-700
            hover:file:bg-green-100">
            <div class="mt-2 text-center">
                <img id="banner-preview" src="" alt="Banner Preview" class="mx-auto" style="width: 120px !important; display: none;">
                <span class="text-gray-500 text-sm">No image selected</span>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="return-policy">Return & Refund Policy</label>
        <div class="flex items-center gap-4">
            <textarea id="return-policy" name="return_policy" rows="5" placeholder="Enter policy text"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">{{ $storeDetails['return_policy'] ?? '' }}</textarea>
            <span class="text-gray-500">OR</span>
            <input type="file" id="return-policy-file" name="return_policy_file" accept=".pdf,.doc,.docx" class="block text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-md file:border-0
            file:text-sm file:font-semibold
            file:bg-green-50 file:text-green-700
            hover:file:bg-green-100">
        </div>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="shipping-policy">Shipping Policy</label>
        <div class="flex items-center gap-4">
            <textarea id="shipping-policy" name="shipping_policy" rows="5" placeholder="Enter policy text"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">{{ $storeDetails['shipping_policy'] ?? '' }}</textarea>
            <span class="text-gray-500">OR</span>
            <input type="file" id="shipping-policy-file" name="shipping_policy_file" accept=".pdf,.doc,.docx" class="block text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-md file:border-0
            file:text-sm file:font-semibold
            file:bg-green-50 file:text-green-700
            hover:file:bg-green-100">
        </div>
    </div>

    <div class="flex justify-between mt-8">
        <button type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 btn-back"
            data-prev-tab="basic-info">Back</button>
        <button type="button"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 btn-next"
            data-next-tab="address">Save & Continue</button>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {
    const saveBtn = document.querySelector(".btn-next[data-next-tab='address']");
    if (!saveBtn) return;

    // Directly get Laravel CSRF token
    const csrfToken = "{{ csrf_token() }}";

    saveBtn.addEventListener("click", function () {
        const formData = new FormData();

        // Collect text & select inputs
        formData.append("business_type", document.querySelector("#business-type")?.value || "");
        formData.append("store_category", document.querySelector("#store-category")?.value || "");
        formData.append("store_description", document.querySelector("#store-description")?.value || "");
        formData.append("return_policy", document.querySelector("#return-policy")?.value || "");
        formData.append("shipping_policy", document.querySelector("#shipping-policy")?.value || "");

        // Collect files if present
        const logo = document.querySelector("#store-logo");
        if (logo && logo.files.length > 0) {
            formData.append("store_logo", logo.files[0]);
        }

        const banner = document.querySelector("#store-banner");
        if (banner && banner.files.length > 0) {
            formData.append("store_banner", banner.files[0]);
        }

        const returnPolicyFile = document.querySelector("#return-policy-file");
        if (returnPolicyFile && returnPolicyFile.files.length > 0) {
            formData.append("return_policy_file", returnPolicyFile.files[0]);
        }

        const shippingPolicyFile = document.querySelector("#shipping-policy-file");
        if (shippingPolicyFile && shippingPolicyFile.files.length > 0) {
            formData.append("shipping_policy_file", shippingPolicyFile.files[0]);
        }

        // Send request to Laravel route
        fetch("{{ route('vendor.store.update') }}", {
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
                showSuccess('Store details updated successfully!');
                
                // ✅ Update logo/banner previews if returned
                if (data.logo_url) {
                    const logoPreview = document.getElementById('logo-preview');
                    logoPreview.src = data.logo_url + '?t=' + new Date().getTime();
                    logoPreview.style.display = 'block';
                }
                if (data.banner_url) {
                    const bannerPreview = document.getElementById('banner-preview');
                    bannerPreview.src = data.banner_url + '?t=' + new Date().getTime();
                    bannerPreview.style.display = 'block';
                }

                // Optionally move to next tab
                // document.querySelector(`[data-tab='address']`)?.click();
            } else {
                showError(data.message || 'Failed to update store details.');
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showError('Something went wrong while saving store details.');
        });
    });
});
</script>
