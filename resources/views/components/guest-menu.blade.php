<!-- 🔹 START: Header Buttons -->
<div class="flex items-center ml-auto">

    <!-- 🔸 SIGN UP DROPDOWN -->
    <div class="relative group">
        <button 
            class="text-gray-600 hover:text-[#E85D85] px-2 sm:px-3 py-2 text-xs sm:text-sm font-semibold transition duration-150 flex items-center focus:outline-none"
            onclick="toggleDropdownMenu(this, event)"
        >
            <span class="hover-text flex items-center">Sign Up <i class="fa fa-caret-down ml-1 text-[10px]"></i></span>
        </button>

        <div 
            class="absolute right-0 mt-1 w-44 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 hidden group-hover:block"
            style="z-index: 100;"
        >
            <a href="/login-user?type=customer-signup&page={{ request()->path() }}" 
               class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-50 hover:text-[#E85D85] text-left transition duration-150"
               onclick="userType('customer', 'sign')">Customer Sign Up</a>

            <a href="/login-user?type=vendor-signup&page={{ request()->path() }}" 
               class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-50 hover:text-[#E85D85] text-left transition duration-150"
               onclick="userType('vendor', 'sign')">Vendor Sign Up</a>
        </div>
    </div>

    <div class="text-gray-300 mx-1 select-none">|</div>

    <!-- 🔸 LOGIN DROPDOWN -->
    <div class="relative group">
        <button 
            class="text-gray-600 hover:text-[#E85D85] px-2 sm:px-3 py-2 text-xs sm:text-sm font-semibold transition duration-150 flex items-center focus:outline-none"
            onclick="toggleDropdownMenu(this, event)"
        >
            <span class="hover-text flex items-center">Login <i class="fa fa-caret-down ml-1 text-[10px]"></i></span>
        </button>

        <div 
            class="absolute right-0 mt-1 w-44 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 hidden group-hover:block"
            style="z-index: 100;"
        >
            <a href="/login-user?type=customer-login&page={{ request()->path() }}" 
               class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-50 hover:text-[#E85D85] text-left transition duration-150" onclick="userType('customer', 'log')">Customer Login</a>

            <a href="/login-user?type=vendor-login&page={{ request()->path() }}" 
               class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-50 hover:text-[#E85D85] text-left transition duration-150" onclick="userType('vendor', 'log')">Vendor Login</a>
        </div>
    </div>
</div>

<!-- 🔹 END: Header Buttons -->

<!-- ✅ SCRIPT SECTION -->
<script>
    // Toggle dropdown on click / touch tap
    function toggleDropdownMenu(button, event) {
        event.stopPropagation();
        const dropdown = button.nextElementSibling;
        const isHidden = dropdown.classList.contains('hidden');
        
        // Close all other dropdowns
        document.querySelectorAll('.group .absolute').forEach(drop => {
            if (drop !== dropdown) {
                drop.classList.add('hidden');
            }
        });
        
        if (isHidden) {
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    }

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('.group .absolute').forEach(drop => {
            drop.classList.add('hidden');
        });
    });
</script>
