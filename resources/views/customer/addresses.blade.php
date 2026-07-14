<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Addresses | Multivendor Platform</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- font-awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <style>
        /* Internal CSS */
        .sidebar-item.active {
            background-color: #f3f4f6;
            border-right: 3px solid #3b82f6;
            color: #3b82f6;
        }
        .address-card {
            transition: all 0.3s ease;
        }
        .address-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .default-address {
            border-left: 4px solid #10b981;
        }
        
        .address-form {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
            display: none;
        }
        
        .address-form.open {
            display: block;
            max-height: 1000px;
        }
        
        /* Animation for notifications */
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        .notification {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-customer.sidebar :basic_info="$basic_info"/>
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <!-- Left side - Mobile menu and title -->
                <div class="flex items-center">
                    <button class="hidden mr-4 text-gray-500 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Addresses</h1>
                </div>

                <!-- Center - Search bar -->
                <div class="hidden md:flex flex-1 max-w-md mx-4">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search..."
                            class="w-full py-2 pl-4 pr-10 text-sm bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                        <button class="absolute right-3 top-2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Right side - Icons and user menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Notification dropdown -->
                    <div class="relative">
                        <button id="notification-button"
                            class="p-2 text-gray-500 rounded-full hover:bg-gray-100 relative focus:outline-none">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Notification dropdown menu -->
                        <div id="notification-dropdown"
                            class="hidden absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-10 border border-gray-200">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-sm font-medium text-gray-700">Notifications</h3>
                            </div>
                            <div class="divide-y divide-gray-100">
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">New message</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">You received a new message from
                                        Sarah</div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">System update</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">Your system will be updated tonight
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">Payment received</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">Your payment of $29.99 has been
                                        processed</div>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-gray-200 bg-gray-50 text-center">
                                <a href="./notifications.php"
                                    class="text-xs font-medium text-blue-600 hover:text-blue-800">See all
                                    notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- User dropdown -->
                    {{-- <div class="relative">
                        <button id="user-menu-button" class="flex items-center focus:outline-none">
                            <div class="mr-3 text-right hidden sm:block">
                                <span class="block text-sm font-medium text-gray-700"><?= $basic_info['first_name'] . " " . $basic_info['last_name']?></span>
                                <span class="block text-xs text-gray-500">Admin</span>
                            </div>
                            <div class="relative">
                                <img class="w-8 h-8 rounded-full" src="<?= $basic_info['profile_image']?>"
                                    alt="User">
                            </div>
                        </button>
                    </div> --}}
                </div>
                <script src="../script/notification_dropdown.js"></script>
            </header>


            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 pb-24 md:pb-6">
                <!-- Address Management Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Saved Addresses</h2>
                        <p class="text-gray-600">Manage your shipping addresses for faster checkout</p>
                    </div>
                    <button id="add-address-btn" class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add New Address
                    </button>
                </div>
                
                <!-- Add/Edit Address Form -->
                <div id="address-form" class="address-form bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="form-title">Add New Address</h3>
                        <button id="close-form-btn" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form id="new-address-form" action="" method="POST">
                        <input type="hidden" id="address_id" name="address_id" value="">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="address_type" class="block text-sm font-medium text-gray-700 mb-1">Address Type</label>
                                <input type="text" id="address_type" name="address_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Home, Work, etc." >
                            </div>
                            
                            <div>
                                <label for="full-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="full-name" name="full-name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="address-line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                <input type="text" id="address-line1" name="address-line1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="address-line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Optional)</label>
                                <input type="text" id="address-line2" name="address-line2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="city" name="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                <input type="text" id="state" name="state" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="zip-code" class="block text-sm font-medium text-gray-700 mb-1">ZIP/Postal Code</label>
                                <input type="text" id="zip-code" name="zip-code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <select id="country" name="country" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                                    <option value="">Select Country</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="UK">United Kingdom</option>
                                    <option value="AU">Australia</option>
                                    <option value="IN">India</option>
                                    <!-- More countries would be added here -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex items-center">
                            <input type="checkbox" id="default-address" name="default-address" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="default-address" class="ml-2 block text-sm text-gray-700">Set as default shipping address</label>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" id="cancel-form-btn" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                Cancel
                            </button>
                            <button type="submit" name="add_address" id="submit-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none">
                                Save Address
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Address Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="address-con">
                    @if(count($addresses) == 0)
                        <div class="col-span-full text-center py-12">
                            <i class="fas fa-map-marker-alt text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No saved addresses</h3>
                            <p class="text-gray-500 mb-6">Add your addresses for faster checkout</p>
                            
                            <button id="show-empty-add-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none">
                                <i class="fas fa-plus mr-2"></i>Add Address
                            </button>
                        </div>
                    @else
                        @foreach ($addresses as $address)
                            <div class="address-card bg-white rounded-lg shadow {{ $address->is_default ? 'default-address' : '' }}" data-address-id="{{ $address->id }}">
                                <div class="p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            @if ($address->is_default)
                                                <div class="flex items-center mb-2">
                                                    <span class="text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full mr-2">Default</span>
                                                    <h3 class="text-lg font-medium text-gray-900">{{ $address->address_type }}</h3>
                                                </div>
                                            @else
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $address->address_type }}</h3>
                                            @endif
                                            <p class="text-gray-700">{{ $address->full_name }}</p>
                                            <p class="text-gray-700">{{ $address->address_line1 }}</p>
                                            @if (!empty($address->address_line2))
                                                <p class="text-gray-700">{{ $address->address_line2 }}</p>
                                            @endif
                                            <p class="text-gray-700">{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                                            <p class="text-gray-700">{{ $address->country }}</p>
                                            <p class="text-gray-700 mt-2">Phone: {{ $address->phone }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if (!$address->is_default)
                                                <button onclick="setDefaultAddress({{ $address->id }})" class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200">
                                                    Set Default
                                                </button>
                                            @endif
                                            <button onclick="openEditForm({{ $address->id }})" class="p-2 text-blue-600 hover:text-blue-800 focus:outline-none">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteAddress({{ $address->id }})" class="p-2 text-red-500 hover:text-red-700 focus:outline-none">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </main>
            <x-customer.mobile-nav />
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Address
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this address? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="deleteAddress()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Internal JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeAddressForm();
            activateCurrentSidebarLink();
        });

        function activateCurrentSidebarLink() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar-item').forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        }

        function initializeAddressForm() {
            const addBtn = document.getElementById('add-address-btn');
            const addressForm = document.getElementById('address-form');
            const closeFormBtn = document.getElementById('close-form-btn');
            const cancelFormBtn = document.getElementById('cancel-form-btn');
            const showEmptyAddBtn = document.getElementById('show-empty-add-btn');
            
            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    resetForm();
                    addressForm.classList.add('open');
                    addressForm.scrollIntoView({ behavior: 'smooth' });
                });
            }
            
            if (showEmptyAddBtn) {
                showEmptyAddBtn.addEventListener('click', function() {
                    resetForm();
                    addressForm.classList.add('open');
                });
            }
            
            if (closeFormBtn) {
                closeFormBtn.addEventListener('click', function() {
                    addressForm.classList.remove('open');
                });
            }
            
            if (cancelFormBtn) {
                cancelFormBtn.addEventListener('click', function() {
                    addressForm.classList.remove('open');
                });
            }
            
            // Form submission
            const addressFormElement = document.getElementById('new-address-form');
            if (addressFormElement) {
                addressFormElement.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveAddress();
                });
            }
        }

        // Reset form to add new address
        function resetForm() {
            document.getElementById('form-title').textContent = 'Add New Address';
            document.getElementById('new-address-form').reset();
            document.getElementById('address_id').value = '';
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.textContent = 'Save Address';
        }

        // Save or update address
        async function saveAddress() {
            // Get form values directly
            const formData = {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                address_id: document.getElementById('address_id').value,
                address_type: document.getElementById('address_type').value,
                full_name: document.getElementById('full-name').value,
                phone: document.getElementById('phone').value,
                address_line1: document.getElementById('address-line1').value,
                address_line2: document.getElementById('address-line2').value,
                city: document.getElementById('city').value,
                state: document.getElementById('state').value,
                zip_code: document.getElementById('zip-code').value,
                country: document.getElementById('country').value,
                default_address: document.getElementById('default-address').checked ? 1 : 0
            };

            // Validate required fields
            if (!formData.full_name || !formData.phone || !formData.address_line1 || 
                !formData.city || !formData.state || !formData.zip_code || !formData.country || !formData.address_type) {
                showNotification('Please fill all required fields', 'error');
                return;
            }

            // Show loading state
            const submitBtn = document.getElementById('submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Saving...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('/customer/address/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': formData._token
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    document.getElementById('address-form').classList.remove('open');
                    
                    console.log(data.address_id)
                    if (formData.address_id) {
                        // Update existing address in the list
                        console.log('update')
                        updateAddressInList(data.address_id, formData);
                    } else {
                        // console.log('save')
                        // Add new address to the list
                        addNewAddressToList(data.address_id, formData);
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error saving address', 'error');
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        }

        // Add new address to the list without reload
        function addNewAddressToList(addressId, addressData) {
            const addressesContainer = document.querySelector('#address-con');
            
            // Remove empty state if exists
            const emptyState = addressesContainer.querySelector('.col-span-full.text-center');
            if (emptyState) {
                emptyState.remove();
            }
            
            // Create new address card
            const newAddressCard = createAddressCard(addressId, addressData);
            
            // Add to the container
            addressesContainer.appendChild(newAddressCard);
            console.log(addressesContainer)
        }

        // Update existing address in the list
        function updateAddressInList(addressId, addressData) {
            const existingCard = document.querySelector(`[data-address-id="${addressId}"]`);
            if (existingCard) {
                const newCard = createAddressCard(addressId, addressData);
                existingCard.replaceWith(newCard);
            }
        }

        // Create address card HTML
        function createAddressCard(addressId, addressData) {
            const isDefault = addressData.default_address == 1;
            const card = document.createElement('div');
            card.className = `address-card bg-white rounded-lg shadow ${isDefault ? 'default-address' : ''}`;
            card.setAttribute('data-address-id', addressId);
            
            card.innerHTML = `
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            ${isDefault ? `
                                <div class="flex items-center mb-2">
                                    <span class="text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full mr-2">Default</span>
                                    <h3 class="text-lg font-medium text-gray-900">${addressData.address_type}</h3>
                                </div>
                            ` : `
                                <h3 class="text-lg font-medium text-gray-900 mb-2">${addressData.address_type}</h3>
                            `}
                            <p class="text-gray-700">${addressData.full_name}</p>
                            <p class="text-gray-700">${addressData.address_line1}</p>
                            ${addressData.address_line2 ? `<p class="text-gray-700">${addressData.address_line2}</p>` : ''}
                            <p class="text-gray-700">${addressData.city}, ${addressData.state} ${addressData.zip_code}</p>
                            <p class="text-gray-700">${addressData.country}</p>
                            <p class="text-gray-700 mt-2">Phone: ${addressData.phone}</p>
                        </div>
                        <div class="flex space-x-2">
                            ${!isDefault ? `
                                <button onclick="setDefaultAddress(${addressId})" class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200">
                                    Set Default
                                </button>
                            ` : ''}
                            <button onclick="openEditForm(${addressId})" class="p-2 text-blue-600 hover:text-blue-800 focus:outline-none">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteAddress(${addressId})" class="p-2 text-red-500 hover:text-red-700 focus:outline-none">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            return card;
        }

        // Function to populate the edit form
        async function openEditForm(addressId) {
            // Show loading state
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.textContent = 'Loading...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch(`/customer/address/get/${addressId}`);
                const data = await response.json();
                
                if (data.success) {
                    const address = data.address;
                    
                    document.getElementById('form-title').textContent = 'Edit Address';
                    document.getElementById('address_id').value = address.id;
                    document.getElementById('address_type').value = address.address_type;
                    document.getElementById('full-name').value = address.full_name;
                    document.getElementById('phone').value = address.phone;
                    document.getElementById('address-line1').value = address.address_line1;
                    document.getElementById('address-line2').value = address.address_line2 || '';
                    document.getElementById('city').value = address.city;
                    document.getElementById('state').value = address.state;
                    document.getElementById('zip-code').value = address.zip_code;
                    document.getElementById('country').value = address.country;
                    document.getElementById('default-address').checked = address.is_default == 1;
                    
                    document.getElementById('submit-btn').textContent = 'Update Address';
                    
                    // Show the form
                    document.getElementById('address-form').classList.add('open');
                    document.getElementById('address-form').scrollIntoView({ behavior: 'smooth' });
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error loading address', 'error');
            } finally {
                submitBtn.textContent = 'Update Address';
                submitBtn.disabled = false;
            }
        }

        // Delete address function
        async function deleteAddress(addressId) {
            if (confirm('Are you sure you want to delete this address?')) {
                try {
                    const response = await fetch(`/customer/address/delete/${addressId}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification(data.message, 'success');
                        // Remove the address card from DOM
                        const addressCard = document.querySelector(`[data-address-id="${addressId}"]`);
                        if (addressCard) {
                            addressCard.remove();
                        }
                        
                        // If no addresses left, show empty state
                        const addressesContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.gap-6');
                        if (addressesContainer && addressesContainer.children.length === 0) {
                            showEmptyState();
                        }
                    } else {
                        showNotification(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error deleting address', 'error');
                }
            }
        }

        // Show empty state
        function showEmptyState() {
            const addressesContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.gap-6');
            if (addressesContainer) {
                addressesContainer.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-map-marker-alt text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No saved addresses</h3>
                        <p class="text-gray-500 mb-6">Add your addresses for faster checkout</p>
                        <button id="show-empty-add-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none">
                            <i class="fas fa-plus mr-2"></i>Add Address
                        </button>
                    </div>
                `;
                
                // Re-initialize the button event listener
                const newShowEmptyBtn = document.getElementById('show-empty-add-btn');
                if (newShowEmptyBtn) {
                    newShowEmptyBtn.addEventListener('click', function() {
                        resetForm();
                        document.getElementById('address-form').classList.add('open');
                    });
                }
            }
        }

        // Set default address
        async function setDefaultAddress(addressId) {
            try {
                const response = await fetch(`/customer/address/set-default/${addressId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    
                    // Update UI without reload
                    // Remove default class from all addresses
                    document.querySelectorAll('.default-address').forEach(card => {
                        card.classList.remove('default-address');
                    });
                    
                    // Add default class to selected address
                    const selectedCard = document.querySelector(`[data-address-id="${addressId}"]`);
                    if (selectedCard) {
                        selectedCard.classList.add('default-address');
                        
                        // Update the button states
                        updateDefaultButtonStates(addressId);
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error setting default address', 'error');
            }
        }

        // Update default button states
        function updateDefaultButtonStates(defaultAddressId) {
            document.querySelectorAll('[data-address-id]').forEach(card => {
                const addressId = card.getAttribute('data-address-id');
                const isDefault = addressId == defaultAddressId;
                
                // Update the card border
                if (isDefault) {
                    card.classList.add('default-address');
                } else {
                    card.classList.remove('default-address');
                }
                
                // Get the title element
                const title = card.querySelector('h3.text-lg');
                const buttonContainer = card.querySelector('.flex.space-x-2');
                
                if (title) {
                    const titleContainer = title.parentElement;
                    
                    if (isDefault) {
                        // Create/update default badge structure
                        if (!titleContainer.classList.contains('flex') || !titleContainer.classList.contains('items-center')) {
                            // Wrap title in flex container with badge
                            const wrapperDiv = document.createElement('div');
                            wrapperDiv.className = 'flex items-center mb-2';
                            
                            const badge = document.createElement('span');
                            badge.className = 'text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full mr-2';
                            badge.textContent = 'Default';
                            
                            wrapperDiv.appendChild(badge);
                            wrapperDiv.appendChild(title.cloneNode(true));
                            
                            titleContainer.replaceChild(wrapperDiv, title);
                        } else {
                            // Already has flex container, just ensure badge exists
                            const existingBadge = titleContainer.querySelector('span.text-green-600');
                            if (!existingBadge) {
                                const badge = document.createElement('span');
                                badge.className = 'text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full mr-2';
                                badge.textContent = 'Default';
                                titleContainer.insertBefore(badge, titleContainer.firstChild);
                            }
                        }
                    } else {
                        // Remove default badge structure
                        if (titleContainer.classList.contains('flex') && titleContainer.classList.contains('items-center')) {
                            const badge = titleContainer.querySelector('span.text-green-600');
                            if (badge) {
                                // Get the title from the wrapper
                                const titleInWrapper = titleContainer.querySelector('h3.text-lg');
                                if (titleInWrapper) {
                                    // Replace wrapper with just the title
                                    titleContainer.parentElement.replaceChild(titleInWrapper, titleContainer);
                                }
                            }
                        }
                    }
                }
                
                // Update set default button
                if (buttonContainer) {
                    const existingSetDefaultBtn = buttonContainer.querySelector('button.text-xs.bg-gray-100');
                    
                    if (isDefault) {
                        // Remove set default button
                        if (existingSetDefaultBtn) {
                            existingSetDefaultBtn.remove();
                        }
                    } else {
                        // Add set default button if not exists
                        if (!existingSetDefaultBtn) {
                            const setDefaultBtn = document.createElement('button');
                            setDefaultBtn.className = 'text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200';
                            setDefaultBtn.textContent = 'Set Default';
                            setDefaultBtn.onclick = function() { setDefaultAddress(addressId); };
                            
                            // Insert before edit button
                            const editBtn = buttonContainer.querySelector('button:has(.fa-edit)');
                            if (editBtn) {
                                buttonContainer.insertBefore(setDefaultBtn, editBtn);
                            } else {
                                buttonContainer.prepend(setDefaultBtn);
                            }
                        }
                    }
                }
            });
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            document.querySelectorAll('.notification').forEach(notification => {
                notification.remove();
            });

            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg bg-white border-l-4 ${
                type === 'success' ? 'border-green-500' : 
                type === 'error' ? 'border-red-500' : 'border-blue-500'
            } z-50`;
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${
                            type === 'success' ? 'fa-check-circle text-green-500' : 
                            type === 'error' ? 'fa-exclamation-circle text-red-500' : 'fa-info-circle text-blue-500'
                        }"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <div class="ml-4 pl-3 flex-shrink-0 flex">
                        <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Add click event to close button
            const closeBtn = notification.querySelector('button');
            closeBtn.addEventListener('click', function() {
                notification.remove();
            });
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Remove duplicate function definitions
        // Delete the duplicate openEditForm and other duplicate functions at the bottom of your file
    </script>

    <!-- Notification  -->
    <script src="../script/customer_notification.js"></script>
</body>
</html>