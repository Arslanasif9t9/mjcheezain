<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    }
    elseif (isset($_SESSION['type']) && $_SESSION['type'] == "customer") {
        header("Location: ../customer/dashboard.php");
    }

    require_once '../mydatabase/conn.php';
    $user_id = $_SESSION['user_id'];
    @include "./user_block.php";
    // Get basic info
    $stmt = $conn->prepare("SELECT * FROM vendor_basic_info WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $basic_info = $result->fetch_assoc();
    // Default values if data doesn't exist
    $profile_picture = !empty($basic_info['profile_picture']) ? $basic_info['profile_picture'] : '../img/default_profile.webp';
    $full_name = $basic_info['full_name'] ?? 'Not specified';
?>

<?php
    $stmt = $conn->prepare("SELECT * FROM vendor_basic_info WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $basic_info = $result->fetch_assoc();

    // Get store details
    $stmt = $conn->prepare("SELECT * FROM vendor_store_details WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $store_details = $result->fetch_assoc();

    // Get address info
    $stmt = $conn->prepare("SELECT * FROM vendor_address WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $address_info = $result->fetch_assoc();

    // Get user email from users table
    $stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_email = $result->fetch_assoc()['email'] ?? '';$stmt = $conn->prepare("SELECT * FROM vendor_basic_info WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $basic_info = $result->fetch_assoc();

    // Get store details
    $stmt = $conn->prepare("SELECT * FROM vendor_store_details WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $store_details = $result->fetch_assoc();

    // Get address info
    $stmt = $conn->prepare("SELECT * FROM vendor_address WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $address_info = $result->fetch_assoc();

    // Get user email from users table
    $stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_email = $result->fetch_assoc()['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./CDN tailwind.js"></script>
    <!-- font-awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/vendor_navbar.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Custom styles for file upload previews and toggle switch */
        .preview-container img {
            max-width: 200px;
            max-height: 200px;
            display: none;
            margin-bottom: 10px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-left: 15px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        #map-preview {
            height: 200px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        /* Custom animation for modal */
        #logoutModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <button id="btn-side" onclick="navbarToggle(this)"><i class="fas fa-bars m-4"></i></button>
        <aside id="aside" class="w-64 bg-gray-900 text-white p-4">
            <div class="flex flex-col items-center">
                <img class="w-24 h-24 rounded-full object-cover" src="<?php echo $profile_picture; ?>" alt="Profile" />
                <h2 class="mt-4 font-semibold text-xl"><?php echo $full_name; ?></h2>
                <?php if ($basic_info['profile_visibility']) 
                        echo "<span class='active-button mt-1 bg-green-500 px-2 rounded-full'> Active </span>";
                    else echo "<span class='active-button mt-1 bg-red-500 px-2 rounded-full'> Close </span>";
                ?>
                <div class="text-yellow-500 mb-4 text-lg"> ★★★★★ </div>
            </div>
            <nav class="space-y-4">
                <a href="./dashboard.php" class="flex items-center gap-2"><i class="fa fa-chart-bar"></i> Dashboard</a>
                <a href="./products.php" class="flex items-center gap-2"><i
                        class="fa fa-box"></i>
                    Products</a>
                <a href="./orders.php" class="flex items-center gap-2"><i class="fa fa-shopping-cart"></i> Orders</a>
                <!-- <a href="./chat.php" class="flex items-center gap-2"><i class="fa-brands fa-rocketchat"></i> Live
                    Chat</a> -->
                <a href="./withdraw.php" class="flex items-center gap-2"><i class="fa fa-wallet"></i> Withdraw</a>
                <a href="./profile.php" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded"><i class="fa-solid fa-user"></i> Profile</a>
                <a href="#" id="logoutBtn" class="flex items-center gap-2"><i class="fas fa-sign-out-alt"></i> Log out</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="bg-gray-100 flex-1 p-6 overflow-y-auto scrollbar-hide">
            <div class="container mx-auto p-4 max-w-4xl">
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="h-2 bg-gray-300 rounded-full transition-all duration-300">
                        <!-- <p class="absolute text-white text-sm font-bold">25% completed</p> -->
                        <div id="progress-bar" class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 33%;"></div>
                    </div>
                    <div class="flex justify-around mt-2 text-sm">
                        <span class="text-green-600 font-medium">1. Basic Info</span>
                        <span class="text-gray-500">2. Store Details</span>
                        <span class="text-gray-500">3. Address</span>
                        <!-- <span class="text-gray-500">4. Finish</span> -->
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="flex border-b border-gray-200 mb-5">
                    <button class="px-4 py-2 font-medium text-green-600 border-b-2 border-green-500 tab-btn active"
                        data-tab="basic-info">Basic Info</button>
                    <button class="px-4 py-2 font-medium text-gray-500 tab-btn" data-tab="store-details">Store
                        Details</button>
                    <button class="px-4 py-2 font-medium text-gray-500 tab-btn" data-tab="address">Address</button>
                    <!-- <button class="px-4 py-2 font-medium text-gray-500 tab-btn" data-tab="finish">Finish</button> -->
                </div>

                <!-- Form Content -->
                <form id="vendor-profile-form" class="bg-white p-6 rounded-lg shadow-md" action="./update-profile.php" method="POST" enctype="multipart/form-data">
                    <!-- Tab 1: Basic Info -->
                    <div class="tab-content active" id="basic-info">
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
                                    <img id="profile-preview" src="" alt="Profile Preview" class="mx-auto" style="display: none; width: 120px !important;">
                                    <span class="text-gray-500 text-sm">No image selected</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="full-name">Full Name</label>
                            <input type="text" id="full-name" name="full_name" value="<?php echo $basic_info['full_name'] ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="store-name">Store Name</label>
                            <input type="text" id="store-name" name="store_name"  value="<?php echo $basic_info['store_name'] ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="email">Email Address</label>
                            <div class="relative">
                                <input type="email" id="email"   value="<?php echo $basic_info['email'] ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 bg-gray-100">
                                <!-- <span class="absolute right-3 top-2 text-green-600">✔️ Verified</span> -->
                                <!-- OR -->
                                <!-- <span class="absolute right-3 top-2 text-red-500">❌ Not Verified</span> -->
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone"  value="<?php echo $basic_info['phone'] ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div class="mb-5 flex items-center">
                            <label class="block text-gray-700 font-bold">Profile Visibility (Store Active)</label>
                            <label class="switch ml-3">
                                <input type="checkbox" id="profile-visibility" name="profile_visibility" <?php echo $basic_info['profile_visibility'] ? "checked" : ""; ?>>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="flex justify-end mt-8">
                            <!-- <button type="button" class="text-green-600 hover:underline btn-skip">Skip and complete later</button> -->
                            <button type="button"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 btn-next"
                                data-next-tab="store-details">Save & Continue</button>
                        </div>
                    </div>

                    <!-- Tab 2: Store Details -->
                    <div class="tab-content hidden" id="store-details">
                        <h3 class="text-xl font-bold mb-4">📌 Store Commercial Identity & Policies</h3>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="business-type">Business Type</label>
                            <select id="business-type" name="business_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">Select Business Type</option>
                                <option value="individual">Individual</option>
                                <option value="company">Company</option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="store-category">Store Category</label>
                            <select id="store-category" name="store_category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">Select Category</option>
                                <option value="electronics">Electronics</option>
                                <option value="fashion">Fashion</option>
                                <option value="home">Home & Living</option>
                                <option value="beauty">Beauty</option>
                                <option value="food">Food</option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="store-description">Store Description</label>
                            <textarea id="store-description" name="store_description" rows="5" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"></textarea>
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
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"></textarea>
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
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"></textarea>
                                <span class="text-gray-500">OR</span>
                                <input type="file" id="shipping-policy-file" name="shipping_policy_file" accept=".pdf,.doc,.docx" class="block text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-green-50 file:text-green-700
                                hover:file:bg-green-100">
                            </div>
                        </div>

                        <!-- <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="store-video">Store Video (Optional)</label>
                            <input type="url" id="store-video" name="store_video" placeholder="YouTube video URL"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div> -->

                        <div class="flex justify-between mt-8">
                            <button type="button"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 btn-back"
                                data-prev-tab="basic-info">Back</button>
                            <!-- <button type="button" class="text-green-600 hover:underline btn-skip">Skip and complete later</button> -->
                            <button type="button"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 btn-next"
                                data-next-tab="address">Save & Continue</button>
                        </div>
                    </div>

                    <!-- Tab 3: Address -->
                    <div class="tab-content hidden" id="address">
                        <h3 class="text-xl font-bold mb-4">📌 Delivery & Pickup Details</h3>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="pickup-address">Warehouse / Pickup Address</label>
                            <textarea id="pickup-address" name="pickup_address" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="city">City</label>
                            <select id="city" name="city" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">Select City</option>
                                <option value="karachi">Karachi</option>
                                <option value="lahore">Lahore</option>
                                <option value="islamabad">Islamabad</option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="area">Povince</label>
                            <select id="area" name="area" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">Select province</option>
                                <option value="Punjab">Punjab</option>
                                <option value="Sindh">Sindh</option>
                                <option value="Khyber Pakhtunkhwa">Khyber Pakhtunkhwa</option>
                                <option value="Balochistan">Balochistan</option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="country">Country</label>
                            <input type="text" id="country" value="Pakistan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2" for="postal-code">Postal Code</label>
                            <input type="text" id="postal-code" name="postal_code"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 btn-back"
                                data-prev-tab="address">Back</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Submit</button>
                        </div>
                    </div>

                    <!-- Tab 4: Finish -->
                    <!-- <div class="tab-content hidden" id="finish">
                        <h3 class="text-xl font-bold mb-4">📌 Checklist, Account Connection & Completion Summary</h3>

                        <div class="mb-8">
                            <h4 class="text-lg font-semibold mb-3">🧾 Checklist</h4>
                            <table class="w-full border-collapse">
                                <tr class="border-b border-gray-200">
                                    <td class="py-3">Profile Picture Uploaded</td>
                                    <td class="py-3 text-green-600">✔️</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3">Store Name Added</td>
                                    <td class="py-3 text-green-600">✔️</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3">Return Policy Added</td>
                                    <td class="py-3 text-red-500">❌</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3">CNIC Uploaded</td>
                                    <td class="py-3 text-red-500">❌</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3">Store Banner Uploaded</td>
                                    <td class="py-3 text-green-600">✔️</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3">Store Description Completed</td>
                                    <td class="py-3 text-red-500">❌</td>
                                </tr>
                            </table>
                        </div>

                        <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold mb-3">📊 Summary</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium">Total Completed:</span>
                                    <span>7/10</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Profile Completion:</span>
                                    <span>70%</span>
                                </div>
                                <div class="mt-4 text-red-500 font-medium">
                                    ⚠️ Complete all steps to start receiving orders & payouts.
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 btn-back"
                                data-prev-tab="address">Back</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Submit</button>
                        </div>
                    </div> -->
                </form>
            </div>
        </main>
    </div>



    <!-- logout modal -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Are you sure you want to logout?</h2>
            <p class="text-gray-600 mb-6">You'll need to sign in again to access your account.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelBtn"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button id="confirmLogout"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                    Yes, Logout
                </button>
            </div>
        </div>
    </div>
    <script src="../script/logout.js"></script>

    <script src="../script/vendor_edit-profile.js"></script>
    <script src="../script/vendor_navbar.js"></script>
</body>

</html>