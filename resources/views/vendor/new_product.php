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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard UI</title>
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
        
        /* Custom file input styling */
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
            height: 150px;
            background-color: #f9fafb;
        }
        
        .file-upload-label:hover {
            border-color: #9ca3af;
            background-color: #f3f4f6;
        }
        
        .file-upload-label i {
            font-size: 2rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        
        .file-upload-label span {
            color: #6b7280;
        }
        
        .image-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .image-preview {
            position: relative;
            border-radius: 0.375rem;
            overflow: hidden;
            height: 120px;
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .remove-image {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background-color: rgba(239, 68, 68, 0.8);
            color: white;
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .image-preview:hover .remove-image {
            opacity: 1;
        }
        
        /* Button styles */
        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-add:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
        }
        
        .btn-add i {
            font-size: 0.875rem;
        }
        
        .btn-remove {
            color: #ef4444;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-remove:hover {
            color: #dc2626;
            text-decoration: underline;
        }
    </style>
    <!-- Add this CSS to your style section -->
    <style>
        /* ... existing styles ... */

        /* New styles for image upload inputs */
        .image-input-container {
            position: relative;
        }
        
        .upload-status {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        
        .status-empty {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
        }
        
        .status-filled {
            background-color: #10b981;
            color: white;
        }
        
        .file-upload.disabled {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .file-upload-label.has-image {
            border-color: #10b981;
            background-color: #ecfdf5;
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
                <a href="./products.php" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded"><i
                        class="fa fa-box"></i>
                    Products</a>
                <a href="./orders.php" class="flex items-center gap-2"><i class="fa fa-shopping-cart"></i> Orders</a>
                <!-- <a href="./chat.php" class="flex items-center gap-2"><i class="fa-brands fa-rocketchat"></i> Live
                    Chat</a> -->
                <a href="./withdraw.php" class="flex items-center gap-2"><i class="fa fa-wallet"></i> Withdraw</a>
                <a href="./profile.php" class="flex items-center gap-2"><i class="fa-solid fa-user"></i> Profile</a>
                <a href="#" id="logoutBtn" class="flex items-center gap-2"><i class="fas fa-sign-out-alt"></i> Log out</a>
            </nav>
        </aside>

        <main class="flex-1 p-6 overflow-y-auto scrollbar-hide">
            <div class="max-[90%] mx-auto p-6 text-gray-800">
                <h1 class="text-3xl font-semibold mb-6">New Product</h1>

                <form class="space-y-5" id="productForm" action="submit_product.php" method="POST" enctype="multipart/form-data">
                    <!-- Update the image upload section in your HTML -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h2 class="text-xl font-semibold mb-4">Product Images</h2>
                        <p class="text-sm text-gray-500 mb-4">Minimum 5, Maximum 10 images required</p>
                        
                        <!-- Required Images (5 inputs) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6" id="requiredImagesContainer">
                            <!-- 5 required image inputs will be added here -->
                        </div>
                        
                        <!-- Additional Images Container -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4" id="additionalImagesContainer">
                            <!-- Additional images will be added here -->
                        </div>
                        
                        <!-- Add More Button -->
                        <div id="addMoreContainer" class="text-center">
                            <button type="button" id="addMoreImagesBtn" class="btn-add">
                                <i class="fas fa-plus"></i> Add More Images
                            </button>
                            <p class="text-sm text-gray-500 mt-2">You can add up to 10 images total</p>
                        </div>
                        
                        <!-- Image Previews -->
                        <div id="imagePreviews" class="image-preview-container mt-6">
                            <!-- Image previews will be shown here -->
                        </div>
                    </div>

                    <!-- Product Name -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <label class="block font-medium mb-1">Product Name*</label>
                        <input type="text" placeholder="Enter product name" name="product_name" required
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <!-- Category Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-4">
                        <!-- Main Category -->
                        <div>
                            <label class="block font-medium mb-1">Category*</label>
                            <select 
                                name="category" 
                                id="mainCategory"
                                required
                                class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                onchange="updateSubcategories()"
                            >
                                <option value="">Select category</option>
                                <option value="Auto Parts & Accessories">🚗 Auto Parts & Accessories</option>
                                <option value="Car Tools & Maintenance">🛠️ Car Tools & Maintenance</option>
                                <option value="Perfumes & Fragrances">🧴 Perfumes & Fragrances</option>
                                <option value="Fitness & Gym Equipment">🏋️ Fitness & Gym Equipment</option>
                                <option value="Women's Fashion">👜 Women's Fashion</option>
                                <option value="Men's Accessories">👔 Men's Accessories</option>
                                <option value="Clothing & Apparel">👕 Clothing & Apparel</option>
                                <option value="Mobile Accessories">📱 Mobile Accessories</option>
                                <option value="Home & Living">🏠 Home & Living</option>
                                <option value="Gifts & General Items">🎁 Gifts & General Items</option>
                                <option value="Cosmetics Items">🎁 Cosmetics Items</option>
                            </select>
                        </div>

                        <!-- Subcategory -->
                        <div>
                            <label class="block font-medium mb-1">Subcategory*</label>
                            <select 
                                name="subcategory" 
                                id="subCategory"
                                required
                                disabled
                                class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">First select a category</option>
                            </select>
                        </div>
                    </div>

                    <!-- Brand & Model -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Brand / Car Make*</label>
                                <input type="text" name="brand" placeholder="MJcheezain" required
                                class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label id="modelLabel" class="block font-medium mb-1">------</label>
                                <input type="text" name="model" placeholder="Enter value" required
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />

                            </div>
                        </div>
                    </div>
                                <script>
                                    // Subcategories data
                                    const subcategories = {
                                        "Auto Parts & Accessories": [
                                            "Engine Parts",
                                            "Body Parts",
                                            "Suspension & Steering",
                                            "Brakes & Brake Parts",
                                            "Car Electronics (Speakers, Cameras, etc.)",
                                            "Interior Accessories (Seat Covers, Dashboard, etc.)",
                                            "Exterior Accessories (Fog Lights, Wipers, etc.)",
                                            "Tyres & Wheels",
                                            "Car Cleaning & Care (Polish, Shampoo, etc.)"
                                        ],
                                        "Car Tools & Maintenance": [
                                            "Mechanical Tools",
                                            "Battery Chargers",
                                            "Car Jacks",
                                            "Air Compressors",
                                            "Diagnostic Tools"
                                        ],
                                        "Perfumes & Fragrances": [
                                            "Men Perfumes",
                                            "Women Perfumes",
                                            "Body Mists",
                                            "Fragrance Oils",
                                            "Gift Sets"
                                        ],
                                        "Fitness & Gym Equipment": [
                                            "Dumbbells & Weights",
                                            "Treadmills & Running Machines",
                                            "Resistance Bands",
                                            "Yoga Mats",
                                            "Home Gym Machines",
                                            "Supplements (Protein, Pre-Workout)",
                                            "Water Bottles & Shakers"
                                        ],
                                        "Women's Fashion": [
                                            "Handbags",
                                            "Clutches & Wallets",
                                            "Shoulder Bags",
                                            "Crossbody Bags",
                                            "Women Jewelry (Necklaces, Rings, Earrings)",
                                            "Scarves & Shawls",
                                            "Hair Accessories"
                                        ],
                                        "Men's Accessories": [
                                            "Watches",
                                            "Bracelets",
                                            "Chains",
                                            "Rings",
                                            "Sunglasses",
                                            "Wallets"
                                        ],
                                        "Clothing & Apparel": [
                                            "Men Clothing (Shirts, Pants, Jackets)",
                                            "Women Clothing (Dresses, Tops, Abayas)",
                                            "Kids Clothing (Boys, Girls)",
                                            "Footwear (Men, Women, Kids)"
                                        ],
                                        "Mobile Accessories": [
                                            "Mobile Covers",
                                            "Chargers",
                                            "Handsfree & Earphones",
                                            "Power Banks",
                                            "Screen Protectors"
                                        ],
                                        "Home & Living": [
                                            "Decoration Items",
                                            "LED Lights",
                                            "Clocks",
                                            "Wall Frames",
                                            "Artificial Flowers"
                                        ],
                                        "Gifts & General Items": [
                                            "Keychains",
                                            "Mugs",
                                            "Gift Boxes",
                                            "Custom Printed Items",
                                            "Souvenirs"
                                        ],
                                        "Cosmetics Items": [
                                            "Whitening cream",
                                            "cosmetics official"
                                        ]
                                    };
            
                                    // Categories in order for label logic
                                    const categoryOrder = [
                                        "Auto Parts & Accessories",        // 1
                                        "Car Tools & Maintenance",         // 2
                                        "Perfumes & Fragrances",           // 3
                                        "Fitness & Gym Equipment",         // 4
                                        "Women's Fashion",                 // 5
                                        "Men's Accessories",               // 6
                                        "Clothing & Apparel",               // 7
                                        "Mobile Accessories",              // 8
                                        "Home & Living",                    // 9
                                        "Gifts & General Items",             // 10
                                        "Cosmetics Items"
                                    ];
            
                                    function updateSubcategories() {
                                        const mainCategory = document.getElementById("mainCategory");
                                        const subCategory = document.getElementById("subCategory");
                                        const modelLabel = document.getElementById("modelLabel");
            
                                        // Clear subcategories
                                        subCategory.innerHTML = '<option value="">Select subcategory</option>';
            
                                        // Label logic
                                        if (!mainCategory.value) {
                                            modelLabel.textContent = "------";
                                        } else {
                                            let index = categoryOrder.indexOf(mainCategory.value) + 1;
                                            if (index >= 1 && index <= 2) {
                                                modelLabel.textContent = "Model";
                                            } else if ((index >= 3 && index <= 7) || index == 11) {
                                                modelLabel.textContent = "Size";
                                            } else if (index >= 8 && index <= 10) {
                                                modelLabel.textContent = "New/Used";
                                            }
                                        }
            
                                        // Populate subcategories
                                        if (mainCategory.value) {
                                            subCategory.disabled = false;
                                            subcategories[mainCategory.value].forEach(sub => {
                                                const option = document.createElement("option");
                                                option.value = sub;
                                                option.textContent = sub;
                                                subCategory.appendChild(option);
                                            });
                                        } else {
                                            subCategory.disabled = true;
                                        }
                                    }
                                </script>
                    
                    <!-- Condition -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <label class="block font-medium mb-1">Condition*</label>
                        <select name="condition" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select condition</option>
                            <option>New</option>
                            <option>Used</option>
                            <option>Refurbished</option>
                        </select>
                    </div>

                    <!-- Price & Quantity -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Original Price*</label>
                                <input type="number" name="original_price" placeholder="PKR" required
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Delivery Charges*</label>
                                <input type="number" name="delivery_charges" placeholder="PKR" required
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Selling Price*</label>
                                <input type="number" id="s-pri" name="selling_price" placeholder="PKR" required
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                        </div>
                    </div>

                    <!-- MRP -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <label class="block font-medium mb-1">MRP (optional)*</label>
                        <input type="number" id="mrp" name="mrp" placeholder="Enter minimum selling price"
                            class="w-[87%] border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        <p class="text-right font-bold inline-block w-[12%]">Discount: <span id="mrp-dis"></span>%</p>
                    </div>
                    <script>
                        let sP = document.getElementById('s-pri');
                        let mrp = document.getElementById('mrp');
                        let mrpD = document.getElementById('mrp-dis');
                        mrp.addEventListener('input', ()=>{
                            // console.log(sP.value, mrp.value);
                            let dis = Math.floor((sP.value-mrp.value)/sP.value*100);
                            mrpD.innerHTML = dis;
                        })
                    </script>

                    <!-- Stock -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <label class="block font-medium mb-1">Quantity in Stock*</label>
                        <input type="number" name="quantity" placeholder="Enter quantity" required
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <!-- Shipping -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Shipping Method*</label>
                                <select name="shipping_method" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Select shipping method</option>
                                    <option value="standard">Standard</option>
                                    <option value="express">Express</option>
                                    <option value="local">Local Pickup</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Shipping Time*</label>
                                <input type="text" name="shipping_time" placeholder="e.g. 3-5 business days" required
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                        </div>
                    </div>

                    <!-- Full Description -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <label class="block font-medium mb-1">Description (Minimum 100 words)*</label>
                        <textarea name="description" placeholder="Detailed features, size, compatibility (minimum 100 words)" required
                            class="w-full border border-gray-300 rounded-md p-2 h-32 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" minlength="100"></textarea>
                        <p class="text-sm text-gray-500 mt-1">Word count: <span id="wordCount">0</span>/100</p>
                    </div>

                    <!-- Cards Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="font-medium text-lg">Product Cards (Optional)</h2>
                            <button type="button" id="addCardBtn" class="btn-add">
                                <i class="fas fa-plus"></i> Add Card
                            </button>
                        </div>
                        <div id="cardsContainer" class="space-y-4">
                            <!-- Cards will be added here dynamically -->
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Maximum 4 cards allowed</p>
                    </div>

                    <!-- Faults Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="font-medium text-lg">Product Faults (Optional)</h2>
                            <button type="button" id="addFaultBtn" class="btn-add">
                                <i class="fas fa-plus"></i> Add Fault
                            </button>
                        </div>
                        <div id="faultsContainer" class="space-y-4">
                            <!-- Faults will be added here dynamically -->
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <label class="block font-medium mb-1">Location*</label>
                        <input type="text" name="location" placeholder="Vendor shop or city name" required
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit"
                            class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            Submit Product
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Template (Hidden) -->
            <template id="cardTemplate">
                <div class="card-item border border-gray-200 rounded-md p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block font-medium mb-1">Title</label>
                            <input type="text" name="card-title" placeholder="Card title" 
                                class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Value</label>
                            <input type="text" name="card-value" placeholder="Card value" 
                                class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <button type="button" class="btn-remove remove-card flex items-center gap-1">
                        <i class="fas fa-trash-alt"></i> Remove Card
                    </button>
                </div>
            </template>

            <!-- Fault Template (Hidden) -->
            <template id="faultTemplate">
                <div class="fault-item border border-gray-200 rounded-md p-4 bg-gray-50">
                    <div class="mb-3">
                        <label class="block font-medium mb-1">Fault Image</label>
                        <div class="file-upload">
                            <input type="file" accept="image/*" class="file-upload-input" />
                            <label for="file" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload fault image</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium mb-1">Fault Description</label>
                        <textarea placeholder="Describe the fault" 
                            class="w-full border border-gray-300 rounded-md p-2 h-20 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <button type="button" class="btn-remove remove-fault flex items-center gap-1">
                        <i class="fas fa-trash-alt"></i> Remove Fault
                    </button>
                </div>
            </template>

            <!-- Image Input Template (Hidden) -->
            <template id="imageInputTemplate">
                <div class="file-upload">
                    <input type="file" accept="image/*" class="file-upload-input" />
                    <label for="file" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Click to upload</span>
                    </label>
                </div>
            </template>

            <script>
                // Initialize the form with 5 required image inputs
                document.addEventListener('DOMContentLoaded', function() {
                    initializeImageInputs();
                    
                    // Word count for description
                    document.querySelector('textarea[minlength="100"]').addEventListener('input', function() {
                        const wordCount = this.value.trim().split(/\s+/).length;
                        document.getElementById('wordCount').textContent = wordCount;
                    });
                });

                // Initialize image inputs with status indicators
                function initializeImageInputs() {
                    const requiredContainer = document.getElementById('requiredImagesContainer');
                    const imageInputTemplate = document.getElementById('imageInputTemplate').content;
                    
                    // Add 5 required image inputs
                    for (let i = 0; i < 5; i++) {
                        const containerDiv = document.createElement('div');
                        containerDiv.className = 'image-input-container';
                        
                        // Create status indicator
                        const statusDiv = document.createElement('div');
                        statusDiv.className = 'upload-status status-empty';
                        statusDiv.innerHTML = '<i class="fas fa-plus" style="font-size: 12px;"></i>';
                        
                        // Clone the template
                        const clone = document.importNode(imageInputTemplate, true);
                        const input = clone.querySelector('input');
                        input.required = true;
                        input.name = `productImages[]`;
                        input.dataset.index = i;
                        
                        // Add event listener
                        input.addEventListener('change', handleImageUpload);
                        
                        // Add elements to container
                        containerDiv.appendChild(statusDiv);
                        containerDiv.appendChild(clone);
                        requiredContainer.appendChild(containerDiv);
                    }
                }

                // Image upload handling
                function handleImageUpload(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    const inputIndex = e.target.dataset.index;
                    const container = e.target.closest('.image-input-container');
                    const statusIndicator = container.querySelector('.upload-status');
                    const fileUploadLabel = container.querySelector('.file-upload-label');
                    
                    // Update status indicator
                    statusIndicator.className = 'upload-status status-filled';
                    statusIndicator.innerHTML = '<i class="fas fa-check" style="font-size: 12px;"></i>';
                    
                    // Style the file upload area to show it has an image
                    fileUploadLabel.classList.add('has-image');
                    
                    // Instead of disabling, make it readonly and change appearance
                    e.target.style.opacity = '0.5';
                    e.target.style.pointerEvents = 'none';
                    
                    const previewContainer = document.getElementById('imagePreviews');
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'image-preview';
                        previewDiv.dataset.inputIndex = inputIndex;
                        previewDiv.innerHTML = `
                            <img src="${event.target.result}" alt="Preview" />
                            <div class="remove-image" title="Remove image">
                                <i class="fas fa-times"></i>
                            </div>
                        `;
                        
                        // Add remove functionality
                        previewDiv.querySelector('.remove-image').addEventListener('click', function() {
                            // Re-enable the input
                            const input = container.querySelector('input');
                            input.style.opacity = '1';
                            input.style.pointerEvents = 'auto';
                            input.value = '';
                            
                            // Reset status indicator
                            statusIndicator.className = 'upload-status status-empty';
                            statusIndicator.innerHTML = '<i class="fas fa-plus" style="font-size: 12px;"></i>';
                            
                            // Remove has-image class
                            fileUploadLabel.classList.remove('has-image');
                            
                            // Remove preview
                            previewDiv.remove();
                            
                            // Validate image count
                            validateImageCount();
                        });
                        
                        previewContainer.appendChild(previewDiv);
                        validateImageCount();
                    };
                    
                    reader.readAsDataURL(file);
                }

                // Add more images functionality
                let totalImages = 5;
                document.getElementById('addMoreImagesBtn').addEventListener('click', function() {
                    if (totalImages >= 10) return;
                    
                    const container = document.getElementById('additionalImagesContainer');
                    const containerDiv = document.createElement('div');
                    containerDiv.className = 'image-input-container';
                    
                    // Create status indicator
                    const statusDiv = document.createElement('div');
                    statusDiv.className = 'upload-status status-empty';
                    statusDiv.innerHTML = '<i class="fas fa-plus" style="font-size: 12px;"></i>';
                    
                    // Clone the template
                    const template = document.getElementById('imageInputTemplate').content;
                    const clone = document.importNode(template, true);
                    
                    const input = clone.querySelector('input');
                    input.name = `productImages[]`;
                    input.dataset.index = totalImages;
                    input.addEventListener('change', handleImageUpload);
                    
                    // Add elements to container
                    containerDiv.appendChild(statusDiv);
                    containerDiv.appendChild(clone);
                    container.appendChild(containerDiv);
                    
                    totalImages++;
                    
                    // Hide button if we've reached max
                    if (totalImages >= 10) {
                        document.getElementById('addMoreContainer').classList.add('hidden');
                    }
                });

                // Validate we have at least 5 images
                function validateImageCount() {
                    const fileInputs = document.querySelectorAll('input[name="productImages[]"]');
                    let filledInputs = 0;
                    
                    fileInputs.forEach(input => {
                        if (input.files.length > 0) {
                            filledInputs++;
                        }
                    });
                    
                    const submitBtn = document.getElementById('productForm').querySelector('button[type="submit"]');
                    submitBtn.disabled = filledInputs < 5;
                    
                    return filledInputs >= 5;
                }

                // Card management
                let cardCount = 0;
                document.getElementById('addCardBtn').addEventListener('click', function () {
                    if (cardCount >= 4) {
                        alert('Maximum 4 cards allowed');
                        return;
                    }

                    const template = document.getElementById('cardTemplate');
                    const clone = document.importNode(template.content, true);
                    const container = document.getElementById('cardsContainer');

                    // Assign dynamic names to input fields
                    const titleInput = clone.querySelectorAll('input')[0];
                    const valueInput = clone.querySelectorAll('input')[1];

                    console.log (clone.querySelectorAll[0]);
                    titleInput.name = `cards[${cardCount}][title]`;
                    valueInput.name = `cards[${cardCount}][value]`;

                    // Add remove functionality
                    clone.querySelector('.remove-card').addEventListener('click', function () {
                        this.closest('.card-item').remove();
                        cardCount--;
                        updateCardInputNames(); // re-index names after removal
                    });

                    container.appendChild(clone);
                    cardCount++;
                });

                // Re-index input names when a card is removed
                function updateCardInputNames() {
                    const cardItems = document.querySelectorAll('#cardsContainer .card-item');
                    cardItems.forEach((item, index) => {
                        const titleInput = item.querySelector('input.card-title');
                        const valueInput = item.querySelector('input.card-value');

                        titleInput.name = `cards[${index}][title]`;
                        valueInput.name = `cards[${index}][value]`;
                    });

                    cardCount = cardItems.length;
                }


                // Fault management
                document.getElementById('addFaultBtn').addEventListener('click', function () {
                    const template = document.getElementById('faultTemplate');
                    const clone = document.importNode(template.content, true);
                    const container = document.getElementById('faultsContainer');

                    // Select file input and description textarea
                    const fileInput = clone.querySelector('.file-upload-input');
                    const descriptionTextarea = clone.querySelector('textarea');

                    // ✅ Set flat names so PHP can process as arrays
                    fileInput.name = "faults[]";
                    descriptionTextarea.name = "fault_descriptions[]";

                    // 📸 Image upload preview
                    fileInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        const label = this.closest('.file-upload').querySelector('.file-upload-label');
                        const reader = new FileReader();

                        reader.onload = function (event) {
                            label.innerHTML = `
                                <img src="${event.target.result}" class="w-full h-full object-cover rounded" alt="Preview" />
                            `;
                        };

                        reader.readAsDataURL(file);
                    });

                    // 🗑️ Remove the fault block
                    clone.querySelector('.remove-fault').addEventListener('click', function () {
                        this.closest('.fault-item').remove();
                    });

                    container.appendChild(clone);
                });


                // Form submission
                document.getElementById('productForm').addEventListener('submit', function(e) {
                    if (!validateImageCount()) {
                        e.preventDefault();
                        alert('Please upload at least 5 images');
                        return false;
                    }
                    
                    // Collect card data
                    const cards = [];
                    document.querySelectorAll('.card-item').forEach(card => {
                        const title = card.querySelector('input[type="text"]:nth-child(1)').value;
                        const value = card.querySelector('input[type="text"]:nth-child(2)').value;
                        if (title && value) {
                            cards.push({ title, value });
                        }
                    });
                    
                    // Collect fault descriptions
                    const faultDescriptions = [];
                    document.querySelectorAll('.fault-item textarea').forEach(textarea => {
                        faultDescriptions.push(textarea.value);
                    });
                    
                    // Create hidden inputs for cards and fault descriptions
                    cards.forEach((card, index) => {
                        const titleInput = document.createElement('input');
                        titleInput.type = 'hidden';
                        titleInput.name = `cards[${index}][title]`;
                        titleInput.value = card.title;
                        this.appendChild(titleInput);
                        
                        const valueInput = document.createElement('input');
                        valueInput.type = 'hidden';
                        valueInput.name = `cards[${index}][value]`;
                        valueInput.value = card.value;
                        this.appendChild(valueInput);
                    });
                    
                    faultDescriptions.forEach((desc, index) => {
                        const descInput = document.createElement('input');
                        descInput.type = 'hidden';
                        descInput.name = `fault_descriptions[${index}]`;
                        descInput.value = desc;
                        this.appendChild(descInput);
                    });
                    
                    document.getElementById('productForm').submit();
                    console.log('last state');
                    return true;
                });
            </script>
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
                <button id="confirmLogout" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                    Yes, Logout
                </button>
            </div>
        </div>
    </div>
    <script src="../script/logout.js"></script>

    <script src="../script/vendor_navbar.js"></script>
</body>

</html>