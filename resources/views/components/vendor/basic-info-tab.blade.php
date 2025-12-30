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
                <img id="profile-preview" src="{{asset('storage/vendor/profile/' . $basicInfo['profile_picture'])}}" alt="Profile Preview" class="mx-auto" style="display: none; width: 120px !important;">
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const saveBtn = document.querySelector(".btn-next[data-next-tab='store-details']");

    if (!saveBtn) return;

    // CSRF token directly from Laravel Blade
    const csrfToken = "{{ csrf_token() }}";

    saveBtn.addEventListener("click", function () {
        const formData = new FormData();
        
        const profilePicture = document.querySelector("#profile-picture");
        if (profilePicture && profilePicture.files.length > 0) {
            formData.append("profile_picture", profilePicture.files[0]);
        }
        // console.log(profilePicture.files[0]);

        formData.append("full_name", document.querySelector("#full-name")?.value || "");
        formData.append("store_name", document.querySelector("#store-name")?.value || "");
        formData.append("email", document.querySelector("#email")?.value || "");
        formData.append("phone", document.querySelector("#phone")?.value || "");
        formData.append("profile_visibility", document.querySelector("#profile-visibility")?.checked ? 1 : 0);

        fetch("{{ route('vendor.basic.update') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Inside your .then() block after successful update
            if (data.success) {
                showSuccess('Profile updated successfully!');
                document.querySelector('#full-name-sidebar').innerText = document.querySelector("#full-name")?.value;
                
                // Handle profile visibility
                const isActive = document.querySelector("#profile-visibility")?.checked;
                document.querySelector('.visi').innerText = isActive ? "Active" : "Close";
                document.querySelector('.visi').classList.add(isActive ? "bg-green-500" : "bg-red-500");
                document.querySelector('.visi').classList.remove(isActive ? "bg-red-500" : "bg-green-500");
                
                // Set profile image in aside/sidebar
                const profilePicInput = document.querySelector("#profile-picture");
                const asideImg = document.querySelector('#aside img');
                
                if (profilePicInput.files.length > 0) {
                    // If new file was uploaded, use the local preview
                    const file = profilePicInput.files[0];
                    asideImg.src = URL.createObjectURL(file);
                } else if (data.profile_picture_url) {
                    // If server returned the uploaded image URL
                    asideImg.src = data.profile_picture_url;
                }
            } else {
                showError('Something')
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showError('Something went wrong!')
        });
    });
});
</script>
