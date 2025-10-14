<!-- 🔹 START: Header Buttons -->
<div class="text-right flex self-end bg-gray-400 text-white rounded-[5px] my-1 px-2 py-1 ml-auto">

    <!-- 🔸 SIGN UP DROPDOWN -->
    <div class="relative group">
        <button 
            class="text-white px-3 py-2 text-sm font-medium flex items-center"
            onmouseover="showDropdown(this)"
            onmouseout="hideDropdown(this)"
        >
            <span class="hover-text">Sign Up &nbsp; <i class="fa fa-caret-down"></i></span>
        </button>

        <div 
            class="absolute w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden"
            style="z-index: 100;"
            onmouseover="keepDropdown(this, true)"
            onmouseout="keepDropdown(this, false)"
        >
            <a href="#" 
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 text-left"
               onclick="userType('customer')">Customer Sign Up</a>

            <a href="#" 
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 text-left"
               onclick="userType('vendor')">Vendor Sign Up</a>
        </div>
    </div>

    <div class="text-gray-500 mx-1">|</div>

    <!-- 🔸 LOGIN DROPDOWN -->
    <div class="relative group">
        <button 
            class="text-white px-3 py-2 text-sm font-medium flex items-center"
            onmouseover="showDropdown(this)"
            onmouseout="hideDropdown(this)"
        >
            <span class="hover-text">Login &nbsp; <i class="fa fa-caret-down"></i></span>
        </button>

        <div 
            class="absolute w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden"
            style="z-index: 100;"
            onmouseover="keepDropdown(this, true)"
            onmouseout="keepDropdown(this, false)"
        >
            <a href="#" 
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 text-left">Customer Login</a>

            <a href="#" 
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 text-left">Vendor Login</a>
        </div>
    </div>
</div>

<!-- 🔹 END: Header Buttons -->


<!-- Login/Signup Modal -->
{{-- <div class="hidde"> --}}
    <x-auth-modal />
{{-- </div> --}}

<!-- ✅ SCRIPT SECTION -->
<script>
    // Show dropdown on hover
    function showDropdown(button) {
        const text = button.querySelector('.hover-text');
        const dropdown = button.nextElementSibling;
        if (text) text.style.color = 'blue';
        if (dropdown) dropdown.classList.remove('hidden');
    }

    // Hide dropdown when leaving button (if not hovering dropdown)
    function hideDropdown(button) {
        const text = button.querySelector('.hover-text');
        const dropdown = button.nextElementSibling;
        if (text) text.style.color = 'white';
        if (dropdown) {
            setTimeout(() => {
                if (!dropdown.matches(':hover') && !button.matches(':hover')) {
                    dropdown.classList.add('hidden');
                }
            }, 150);
        }
    }

    // Keep dropdown open while hovering over it
    function keepDropdown(dropdown, hovering) {
        if (!hovering) {
            setTimeout(() => {
                if (!dropdown.previousElementSibling.matches(':hover') && !dropdown.matches(':hover')) {
                    dropdown.classList.add('hidden');
                }
            }, 150);
        } else {
            dropdown.classList.remove('hidden');
        }
    }
</script>
