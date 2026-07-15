@props(['storeDetails' => []])

<div class="tab-content {{ $attributes->get('class') }}" id="store-details">
    <h3 class="text-xl font-bold mb-4">📌 Store Commercial Identity & Policies</h3>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="business-type">Business Type</label>
        <select id="business-type" name="business_type" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-300 focus:border-pink-300">
            <option value="">Select Business Type</option>
            <option value="individual" {{ ($storeDetails['business_type'] ?? '') == 'individual' ? 'selected' : '' }}>Individual</option>
            <option value="company" {{ ($storeDetails['business_type'] ?? '') == 'company' ? 'selected' : '' }}>Company</option>
        </select>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="store-category">Store Category</label>
        <select id="store-category" name="store_category" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-300 focus:border-pink-300">
            <option value="">Select Category</option>
            <option value="electronics" {{ ($storeDetails['store_category'] ?? '') == 'electronics' ? 'selected' : '' }}>Electronics</option>
            <option value="fashion" {{ ($storeDetails['store_category'] ?? '') == 'fashion' ? 'selected' : '' }}>Fashion & Clothing</option>
            <option value="beauty" {{ ($storeDetails['store_category'] ?? '') == 'beauty' ? 'selected' : '' }}>Beauty & Personal Care</option>
            <option value="home" {{ ($storeDetails['store_category'] ?? '') == 'home' ? 'selected' : '' }}>Home & Living</option>
            <option value="grocery" {{ ($storeDetails['store_category'] ?? '') == 'grocery' ? 'selected' : '' }}>Grocery & Daily Needs</option>
            <option value="automotive" {{ ($storeDetails['store_category'] ?? '') == 'automotive' ? 'selected' : '' }}>Automotive</option>
            <option value="sports" {{ ($storeDetails['store_category'] ?? '') == 'sports' ? 'selected' : '' }}>Sports & Fitness</option>
            <option value="baby" {{ ($storeDetails['store_category'] ?? '') == 'baby' ? 'selected' : '' }}>Baby & Kids</option>
            <option value="books" {{ ($storeDetails['store_category'] ?? '') == 'books' ? 'selected' : '' }}>Books & Stationery</option>
            <option value="tools" {{ ($storeDetails['store_category'] ?? '') == 'tools' ? 'selected' : '' }}>Tools & Hardware</option>
            <option value="services" {{ ($storeDetails['store_category'] ?? '') == 'services' ? 'selected' : '' }}>Services</option>
            <option value="food" {{ ($storeDetails['store_category'] ?? '') == 'food' ? 'selected' : '' }}>Food</option>
        </select>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="ntn">NTN Number</label>
        <input type="text" id="ntn" name="ntn" placeholder="i.e:- 12345678" value="{{ $storeDetails['ntn'] ?? '' }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-300 focus:border-pink-300">
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="store-description">
            Store Description
            <span class="text-sm font-normal text-gray-500">(Maximum 100 words)</span>
        </label>
        <div class="relative">
            <textarea id="store-description" name="store_description" rows="5" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-300 focus:border-pink-300 pr-10"
                placeholder="Describe your store in 100 words or less..."
                oninput="updateWordCount()">{{ $storeDetails['store_description'] ?? '' }}</textarea>
            
            <div class="absolute bottom-2 right-2 flex items-center space-x-2">
                <span id="word-count" class="text-sm text-gray-500 font-medium bg-white px-2 py-1 rounded">0/100 words</span>
                <span id="char-count" class="text-sm text-gray-400 bg-white px-2 py-1 rounded">0 chars</span>
            </div>
        </div>
        
        <div class="mt-2">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="word-progress" class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
            </div>
        </div>
        
        <p class="text-xs text-gray-500 mt-1" id="word-limit-warning" style="display: none;">
            <span class="text-red-500">⚠️</span> Word limit reached! You cannot add more text.
        </p>
    </div>

    <div class="mb-5">
        <label class="block text-gray-700 font-bold mb-2" for="store-logo">Store Logo</label>
        <div class="mt-1">
            <input type="file" id="store-logo" name="store_logo" accept="image/*" class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-md file:border-0
            file:text-sm file:font-semibold
            file:bg-green-100 file:text-green-700
            hover:file:bg-green-100"
            onchange="previewImage(this, 'logo-preview')">
            <div class="mt-2 text-center">
                @if(isset($storeDetails['store_logo']) && $storeDetails['store_logo'])
                    <img id="logo-preview" src="{{ asset('storage/' . $storeDetails['store_logo']) }}" alt="Logo Preview" class="mx-auto" style="width: 120px !important;">
                    <span class="text-gray-500 text-sm">Current logo</span>
                @else
                    <img id="logo-preview" src="" alt="Logo Preview" class="mx-auto" style="width: 120px !important; display: none;">
                    <span class="text-gray-500 text-sm">No image selected</span>
                @endif
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
            hover:file:bg-green-100"
            onchange="previewImage(this, 'banner-preview')">
            <div class="mt-2 text-center">
                @if(isset($storeDetails['store_banner']) && $storeDetails['store_banner'])
                    <img id="banner-preview" src="{{ asset('storage/' . $storeDetails['store_banner']) }}" alt="Banner Preview" class="mx-auto" style="width: 120px !important;">
                    <span class="text-gray-500 text-sm">Current banner</span>
                @else
                    <img id="banner-preview" src="" alt="Banner Preview" class="mx-auto" style="width: 120px !important; display: none;">
                    <span class="text-gray-500 text-sm">No image selected</span>
                @endif
            </div>
        </div>
    </div>

    <div class="flex justify-between mt-8">
        <button type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 btn-back"
            data-prev-tab="basic-info">Back</button>
        <button type="button"
            class="px-4 py-2 text-white rounded-full hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:ring-offset-2 btn-next" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 12px rgba(255, 125, 160, 0.35);"
            data-next-tab="address" id="save-store-details">Save & Continue</button>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize word count on page load
    updateWordCount();
    
    // Add event listener to prevent typing beyond word limit
    const textarea = document.getElementById('store-description');
    textarea.addEventListener('keydown', function(e) {
        // Only prevent if we're at the limit and it's a regular character key
        const currentWords = countWords(this.value);
        if (currentWords >= 100) {
            // Allow backspace, delete, arrow keys, ctrl/cmd keys
            if (
                e.key.length === 1 && 
                !e.ctrlKey && 
                !e.metaKey && 
                e.key !== 'Backspace' && 
                e.key !== 'Delete' &&
                !e.key.startsWith('Arrow')
            ) {
                e.preventDefault();
                showWordLimitToast();
            }
        }
    });
    
    // Add paste event listener to prevent pasting beyond word limit
    textarea.addEventListener('paste', function(e) {
        const currentWords = countWords(this.value);
        const clipboardData = e.clipboardData || window.clipboardData;
        const pastedText = clipboardData.getData('text');
        const pastedWords = countWords(pastedText);
        
        if (currentWords + pastedWords > 100) {
            e.preventDefault();
            showWordLimitToast();
        }
    });
    
    const saveBtn = document.getElementById('save-store-details');
    if (!saveBtn) return;

    const csrfToken = "{{ csrf_token() }}";

    saveBtn.addEventListener("click", function () {
        // Validate word count before submitting
        const descriptionText = document.querySelector("#store-description")?.value || "";
        const wordCount = countWords(descriptionText);
        
        if (wordCount > 100) {
            showError('Store description cannot exceed 100 words. Current: ' + wordCount + ' words.');
            return;
        }

        const formData = new FormData();

        // Collect text & select inputs
        formData.append("business_type", document.querySelector("#business-type")?.value || "");
        formData.append("store_category", document.querySelector("#store-category")?.value || "");
        formData.append("ntn", document.querySelector("#ntn")?.value || "");
        formData.append("store_description", descriptionText);

        // Collect files if present
        const logo = document.querySelector("#store-logo");
        if (logo && logo.files.length > 0) {
            formData.append("store_logo", logo.files[0]);
        }

        const banner = document.querySelector("#store-banner");
        if (banner && banner.files.length > 0) {
            formData.append("store_banner", banner.files[0]);
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
                
                // Update logo/banner previews if returned
                if (data.logo_url) {
                    const logoPreview = document.getElementById('logo-preview');
                    logoPreview.src = data.logo_url + '?t=' + new Date().getTime();
                    logoPreview.style.display = 'block';
                    logoPreview.nextElementSibling.textContent = 'Current logo';
                }
                if (data.banner_url) {
                    const bannerPreview = document.getElementById('banner-preview');
                    bannerPreview.src = data.banner_url + '?t=' + new Date().getTime();
                    bannerPreview.style.display = 'block';
                    bannerPreview.nextElementSibling.textContent = 'Current banner';
                }
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

// Function to count words
function countWords(text) {
    if (!text.trim()) return 0;
    // Split by spaces, tabs, newlines and filter out empty strings
    return text.trim().split(/\s+/).length;
}

// Function to update word count display
function updateWordCount() {
    const textarea = document.getElementById('store-description');
    const text = textarea.value;
    
    const wordCount = countWords(text);
    const charCount = text.length;
    
    const wordCountElement = document.getElementById('word-count');
    const charCountElement = document.getElementById('char-count');
    const progressBar = document.getElementById('word-progress');
    const warningElement = document.getElementById('word-limit-warning');
    
    // Update counters
    wordCountElement.textContent = `${wordCount}/100 words`;
    charCountElement.textContent = `${charCount} chars`;
    
    // Update progress bar
    const progressPercentage = Math.min((wordCount / 100) * 100, 100);
    progressBar.style.width = `${progressPercentage}%`;
    
    // Update colors based on word count
    if (wordCount >= 90) {
        wordCountElement.classList.remove('text-gray-500', 'text-yellow-500');
        wordCountElement.classList.add('text-red-500', 'font-bold');
        progressBar.classList.remove('bg-green-600', 'bg-yellow-500');
        progressBar.classList.add('bg-red-500');
    } else if (wordCount >= 75) {
        wordCountElement.classList.remove('text-gray-500', 'text-red-500');
        wordCountElement.classList.add('text-yellow-500');
        progressBar.classList.remove('bg-green-600', 'bg-red-500');
        progressBar.classList.add('bg-yellow-500');
    } else {
        wordCountElement.classList.remove('text-red-500', 'text-yellow-500', 'font-bold');
        wordCountElement.classList.add('text-gray-500');
        progressBar.classList.remove('bg-yellow-500', 'bg-red-500');
        progressBar.classList.add('bg-green-600');
    }
    
    // Show/hide warning
    if (wordCount >= 100) {
        warningElement.style.display = 'block';
    } else {
        warningElement.style.display = 'none';
    }
}

// Function to show word limit toast
function showWordLimitToast() {
    // Create a toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50 animate-bounce';
    toast.textContent = '⚠️ Word limit reached! Maximum 100 words allowed.';
    document.body.appendChild(toast);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Function to preview images
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const noImageText = preview.nextElementSibling;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (noImageText) {
                noImageText.textContent = 'Selected image';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        if (noImageText) {
            noImageText.textContent = 'No image selected';
        }
    }
}


</script>

<style>
#store-description:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

#word-limit-warning {
    transition: all 0.3s ease;
}

.animate-bounce {
    animation: bounce 0.5s;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}
</style>