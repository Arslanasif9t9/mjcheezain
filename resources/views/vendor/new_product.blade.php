<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form | Dynamic Fields</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #6b7280;
            --success: #10b981;
            --danger: #ef4444;
            --light: #f9fafb;
            --dark: #1f2937;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
        
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
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
            background-color: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .btn-add:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .btn-add i {
            font-size: 0.875rem;
        }
        
        .btn-remove {
            color: var(--danger);
            font-weight: 500;
            transition: all 0.2s;
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .btn-remove:hover {
            color: #dc2626;
            text-decoration: underline;
        }
        
        /* Form section styling */
        .form-section {
            background-color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        
        .form-section:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .form-section h2 {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 0.75rem;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: all 0.2s;
            font-size: 0.875rem;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
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
            background-color: var(--success);
            color: white;
        }
        
        .file-upload.disabled {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .file-upload-label.has-image {
            border-color: var(--success);
            background-color: #ecfdf5;
        }
        
        /* Video upload styling */
        .video-upload-label {
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
            cursor: pointer;
        }
        
        .video-upload-label:hover {
            border-color: #9ca3af;
            background-color: #f3f4f6;
        }
        
        .video-upload-label i {
            font-size: 2rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        
        .video-upload-label span {
            color: #6b7280;
        }
        
        .video-preview {
            position: relative;
            border-radius: 0.375rem;
            overflow: hidden;
            height: 500px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-top: 1rem;
        }
        
        .video-preview video {
            width: 100%;
            height: 100%;
            /* object-fit: cover; */
        }
        
        .remove-video {
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
        
        .video-preview:hover .remove-video {
            opacity: 1;
        }
        
        /* Return policy styling */
        .return-policy {
            background-color: #f0f9ff;
            border-left: 4px solid var(--primary);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
        }
        
        .return-policy h3 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .return-policy p {
            color: var(--secondary);
            font-size: 0.875rem;
        }
        
        /* Submit button styling */
        .btn-submit {
            background-color: var(--dark);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .btn-submit:hover {
            background-color: #374151;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        /* Dynamic input styling */
        .dynamic-input-container {
            display: flex;
            gap: 0.5rem;
        }
        
        .dynamic-input-container .form-input {
            flex: 1;
        }
        
        .dynamic-input-container .form-select {
            width: 120px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }
            
            .image-preview-container {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }
            
            .dynamic-input-container {
                flex-direction: column;
            }
            
            .dynamic-input-container .form-select {
                width: 100%;
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar (simplified for demo) -->
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            page='Dashboard'
        />

        <main class="flex-1 p-6 overflow-y-auto scrollbar-hide">
            <div class="max-w-7xl mx-auto p-6 text-gray-800">
                <h1 class="text-3xl font-bold mb-2 text-gray-900">Add New Product</h1>
                <p class="text-gray-600 mb-6">Fill in the details below to list your product</p>

                <form class="space-y-6" id="productForm" action="{{ route('vendor.products.store') }}" method="post" enctype="multipart/form-data" novalidate>
                    @csrf
                    <!-- Product Images Section -->
                    <div class="form-section">
                        <h2>Product Images</h2>
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

                    <!-- Product Video Section -->
                    <div class="form-section">
                        <h2>Product Video</h2>
                        <p class="text-sm text-gray-500 mb-4">Upload a video showcasing your product</p>
                        
                        <div class="video-upload-container">
                            <div class="file-upload">
                                <input type="file" accept="video/*" class="file-upload-input" id="videoUpload" name="productVideo" />
                                <label for="videoUpload" class="video-upload-label" id="videoUploadLabel">
                                    <i class="fas fa-video"></i>
                                    <span>Click to upload product video</span>
                                    <p class="text-xs mt-2">MP4, MOV, AVI (Max 50MB)</p>
                                </label>
                            </div>
                            
                            <!-- Video Preview -->
                            <div id="videoPreview" class="video-preview h- hidden">
                                <video controls>
                                    Your browser does not support the video tag.
                                </video>
                                <div class="remove-video" title="Remove video">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Name -->
                    <div class="form-section">
                        <label class="form-label">Product Name*</label>
                        <input type="text" placeholder="Enter product name" name="product_name" required
                            class="form-input" />
                    </div>

                    <!-- Category Section -->
                    <div class="form-section">
                        <h2>Category & Subcategory</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Main Category -->
                            <div>
                                <label class="form-label">Category*</label>
                                <select 
                                    name="category" 
                                    id="mainCategory"
                                    required
                                    class="form-select"
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
                                    <option value="Cosmetics">💄 Cosmetics</option>
                                </select>
                            </div>

                            <!-- Subcategory -->
                            <div>
                                <label class="form-label">Subcategory*</label>
                                <select 
                                    name="subcategory" 
                                    id="subCategory"
                                    required
                                    disabled
                                    class="form-select"
                                >
                                    <option value="">First select a category</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Brand, Model & Made In Section -->
                    <div class="form-section">
                        <h2>Product Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Brand*</label>
                                <input type="text" name="brand" placeholder="MJ cheezain" required
                                class="form-input" />
                            </div>
                            <div>
                                <label id="modelLabel" class="form-label">------</label>
                                <div id="dynamicInputContainer">
                                    <!-- Dynamic input will be inserted here -->
                                    <input type="text" name="model" placeholder="Enter value" required
                                        class="form-input" />
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Made In*</label>
                                <input type="text" name="made_in" placeholder="Country of origin" required
                                    class="form-input" />
                            </div>
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="form-section">
                        <label class="form-label">Condition*</label>
                        <select name="condition" class="form-select" required>
                            <option value="">Select condition</option>
                            <option>New</option>
                            <option>Used</option>
                            <option>Refurbished</option>
                        </select>
                    </div>

                    <!-- Price & Quantity -->
                    <div class="form-section">
                        <h2>Pricing & Stock</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Original Price*</label>
                                <input type="number" name="original_price" placeholder="PKR" required
                                    class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Delivery Charges*</label>
                                <input type="number" name="delivery_charges" placeholder="PKR" required
                                    class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Selling Price*</label>
                                <input type="number" id="s-pri" name="selling_price" placeholder="PKR" required
                                    class="form-input" />
                            </div>
                        </div>
                    </div>

                    <!-- MRP -->
                    <div class="form-section">
                        <label class="form-label">MRP</label>
                        <div class="flex items-center gap-4">
                            <input type="number" id="mrp" name="mrp" required placeholder="Enter minimum selling price"
                                class="form-input flex-1" />
                            <div class="text-right font-bold">
                                Discount: <span id="mrp-dis" class="text-green-600">0</span>%
                            </div>
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="form-section">
                        <label class="form-label">Quantity in Stock*</label>
                        <input type="number" name="quantity" placeholder="Enter quantity" required
                            class="form-input" />
                    </div>

                    <!-- Shipping -->
                    <div class="form-section">
                        <h2>Shipping Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Shipping Method*</label>
                                <select name="shipping_method" class="form-select" required>
                                    <option value="">Select shipping method</option>
                                    <option value="standard">Standard</option>
                                    <option value="express">Express</option>
                                    <option value="local">Local Pickup</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Shipping Time*</label>
                                <input type="text" name="shipping_time" placeholder="e.g. 3-5 business days" required
                                    class="form-input" />
                            </div>
                        </div>
                    </div>

                    <!-- Full Description -->
                    <div class="form-section">
                        <label class="form-label">Description (Minimum 100 words)*</label>
                        <textarea name="description" placeholder="Detailed features, size, compatibility (minimum 100 words)" required
                            class="form-textarea h-32 resize-none" minlength="100"></textarea>
                        <p class="text-sm text-gray-500 mt-1">Word count: <span id="wordCount">0</span>/100</p>
                    </div>

                    <!-- Return Policy -->
                    <div class="form-section">
                        <h2>Return Policy</h2>
                        <div class="return-policy">
                            <h3>Standard Return Policy</h3>
                            <p>Items can be returned within 7 days of delivery for a full refund, provided they are in original condition with tags attached. Customized or personalized items cannot be returned. Buyer is responsible for return shipping costs unless the item was received damaged or incorrect.</p>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Custom Return Policy (Optional)</label>
                            <textarea name="return_policy" placeholder="Add your custom return policy here (optional)"
                                class="form-textarea h-24 resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Faults Section -->
                    <div class="form-section">
                        <div class="flex justify-between items-center mb-4">
                            <h2>Product Faults (Optional)</h2>
                            <button type="button" id="addFaultBtn" class="btn-add">
                                <i class="fas fa-plus"></i> Add Fault
                            </button>
                        </div>
                        <div id="faultsContainer" class="space-y-4">
                            <!-- Faults will be added here dynamically -->
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="form-section">
                        <label class="form-label">Location*</label>
                        <input type="text" name="location" placeholder="Vendor shop or city name" required
                            class="form-input" />
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right pt-4">
                        <button type="submit" class="btn-submit">
                            Submit Product
                        </button>
                    </div>
                </form>
            </div>

            <style>
                /* Enhanced File Upload Styles */
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
                    z-index: 10;
                }

                .file-upload-label {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    border: 2px dashed #d1d5db;
                    border-radius: 0.75rem;
                    padding: 1.5rem;
                    text-align: center;
                    transition: all 0.3s ease;
                    height: 200px;
                    background-color: #f9fafb;
                    position: relative;
                    overflow: hidden;
                }

                .file-upload-label:hover {
                    border-color: #3b82f6;
                    background-color: #f0f9ff;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
                }

                .file-upload-label i {
                    font-size: 1.75rem;
                    color: #6b7280;
                    margin-bottom: 0.5rem;
                    transition: all 0.3s ease;
                }

                .file-upload-label span {
                    color: #6b7280;
                    font-weight: 500;
                    transition: all 0.3s ease;
                }

                .file-upload-label.has-image {
                    border-color: #10b981;
                    background-color: #ecfdf5;
                    border-style: solid;
                }

                /* Fault Image Preview Styles */
                .fault-image-preview {
                    transition: all 0.3s ease;
                }

                .fault-image-preview.show {
                    display: block;
                    animation: fadeInUp 0.5s ease;
                }

                .fault-upload-content {
                    transition: all 0.3s ease;
                }

                .file-upload-label.has-image .fault-upload-content {
                    opacity: 0;
                }

                /* Fault Item Animations */
                .fault-item {
                    animation: slideIn 0.4s ease-out;
                }

                .fault-item.removing {
                    animation: slideOut 0.3s ease-in forwards;
                }

                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                @keyframes slideOut {
                    from {
                        opacity: 1;
                        transform: translateX(0);
                    }
                    to {
                        opacity: 0;
                        transform: translateX(100%);
                    }
                }

                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                /* Character Counter Styles */
                .char-status {
                    transition: all 0.3s ease;
                }

                .char-status.warning {
                    background-color: #f59e0b;
                    animation: pulse 2s infinite;
                }

                .char-status.danger {
                    background-color: #ef4444;
                    animation: pulse 1s infinite;
                }

                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .fault-item {
                        padding: 1rem;
                    }
                    
                    .file-upload-label {
                        height: 120px;
                        padding: 1rem;
                    }
                    
                    .file-upload-label i {
                        font-size: 1.5rem;
                    }
                }
            </style>
            <!-- Fault Template (Hidden) -->
            <template id="faultTemplate">
                <div class="fault-item border border-gray-200 rounded-lg p-6 bg-white shadow-sm hover:shadow-md transition-all duration-300 mb-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Image Section (Left - Smaller) -->
                        <div class="w-full md:w-1/3">
                            <label class="form-label flex items-center gap-2 mb-3">
                                <i class="fas fa-camera text-blue-500"></i>
                                Fault Image
                            </label>
                            <div class="file-upload group">
                                <input type="file" accept="image/*" class="file-upload-input" name="faults[]" />
                                <label for="file" class="file-upload-label cursor-pointer group-hover:border-blue-400 group-hover:bg-blue-50 transition-all duration-300">
                                    <div class="fault-upload-content">
                                        <i class="fas fa-cloud-upload-alt text-blue-400 group-hover:text-blue-500 transition-colors"></i>
                                        <span class="text-gray-600 group-hover:text-gray-700">Click to upload</span>
                                        <p class="text-xs text-gray-400 mt-2">JPG, PNG, WEBP (Max 5MB)</p>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Image Preview Container -->
                            <div class="fault-image-preview mt-3 hidden" style="display: none !important">
                                <div class="relative rounded-lg overflow-hidden border-2 border-dashed border-green-200 bg-green-50">
                                    <img class="w-full h-32 object-cover" alt="Fault preview" />
                                    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                                        <button type="button" class="remove-fault-image absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300 transform hover:scale-110">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                        <div class="view-full-image opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <button type="button" class="bg-white bg-opacity-90 text-gray-700 px-3 py-1 rounded-full text-xs font-medium hover:bg-opacity-100 transition-all">
                                                <i class="fas fa-expand mr-1"></i> View
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-green-600 text-center mt-2 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Image uploaded
                                </p>
                            </div>
                        </div>

                        <!-- Description Section (Right - Larger) -->
                        <div class="w-full md:w-2/3">
                            <label class="form-label flex items-center gap-2 mb-3">
                                <i class="fas fa-align-left text-purple-500"></i>
                                Fault Description
                            </label>
                            <div class="relative">
                                <textarea placeholder="Describe the fault in detail... (What's wrong, how it affects usage, etc.)" 
                                        name="fault_descriptions[]"
                                        class="form-textarea h-32 resize-none border-2 border-gray-200 focus:border-purple-300 transition-colors duration-300 pr-12"></textarea>
                                
                                <!-- Character counter -->
                                <div class="absolute bottom-3 right-3 flex items-center gap-2">
                                    <div class="text-counter text-xs text-gray-400 font-medium">
                                        <span class="char-count">0</span>/500
                                    </div>
                                    <div class="w-2 h-2 rounded-full bg-gray-300 char-status"></div>
                                </div>
                            </div>
                            
                            <!-- Description tips -->
                            <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-lightbulb text-blue-500 mt-0.5"></i>
                                    <div class="text-xs text-blue-700">
                                        <strong>Tip:</strong> Be specific about the fault. Mention how it affects functionality and any visible damage.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remove Fault Button -->
                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-100">
                        <button type="button" class="btn-remove remove-fault flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all duration-300 hover:shadow-sm">
                            <i class="fas fa-trash-alt"></i>
                            <span>Remove This Fault</span>
                        </button>
                    </div>
                </div>
            </template>

            <script>
                // Enhanced fault management with new features
                document.getElementById('addFaultBtn').addEventListener('click', function () {
                    const template = document.getElementById('faultTemplate');
                    const clone = document.importNode(template.content, true);
                    const container = document.getElementById('faultsContainer');

                    // Get elements
                    const fileInput = clone.querySelector('.file-upload-input');
                    const fileUploadLabel = clone.querySelector('.file-upload-label');
                    const imagePreview = clone.querySelector('.fault-image-preview');
                    const previewImg = imagePreview.querySelector('img');
                    const removeImageBtn = clone.querySelector('.remove-fault-image');
                    const descriptionTextarea = clone.querySelector('textarea');
                    const charCount = clone.querySelector('.char-count');
                    const charStatus = clone.querySelector('.char-status');

                    // Image upload handling
                    fileInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        // Validate file size (5MB max)
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Image size must be less than 5MB');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();

                        reader.onload = function (event) {
                            // Show preview
                            previewImg.src = event.target.result;
                            imagePreview.classList.remove('hidden');
                            imagePreview.classList.add('show');
                            
                            // Update upload label
                            fileUploadLabel.classList.add('has-image');
                            fileUploadLabel.style.backgroundImage = `url(${event.target.result})`;
                            fileUploadLabel.style.backgroundSize = 'cover';
                            fileUploadLabel.style.backgroundPosition = 'center';
                        };

                        reader.readAsDataURL(file);
                    });

                    // Remove image functionality
                    removeImageBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        fileInput.value = '';
                        imagePreview.classList.remove('show');
                        setTimeout(() => imagePreview.classList.add('hidden'), 300);
                        fileUploadLabel.classList.remove('has-image');
                        fileUploadLabel.style.backgroundImage = '';
                    });

                    // Character counter for description
                    descriptionTextarea.addEventListener('input', function () {
                        const count = this.value.length;
                        charCount.textContent = count;
                        
                        // Update status indicator
                        if (count > 400) {
                            charStatus.classList.add('danger');
                            charStatus.classList.remove('warning');
                        } else if (count > 300) {
                            charStatus.classList.add('warning');
                            charStatus.classList.remove('danger');
                        } else {
                            charStatus.classList.remove('warning', 'danger');
                        }
                    });

                    // Remove fault functionality
                    clone.querySelector('.remove-fault').addEventListener('click', function () {
                        const faultItem = this.closest('.fault-item');
                        faultItem.classList.add('removing');
                        setTimeout(() => {
                            faultItem.remove();
                        }, 300);
                    });

                    container.appendChild(clone);
                    
                    // Trigger initial character count
                    descriptionTextarea.dispatchEvent(new Event('input'));
                });
            </script>

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
                // Subcategories data
                const subcategories = {
                    "Auto Parts & Accessories": [
                        "Engine Parts", "Body Parts", "Suspension & Steering", "Brakes & Brake Parts",
                        "Car Electronics (Speakers, Cameras, etc.)", "Interior Accessories (Seat Covers, Dashboard, etc.)",
                        "Exterior Accessories (Fog Lights, Wipers, etc.)", "Tyres & Wheels", "Car Cleaning & Care (Polish, Shampoo, etc.)"
                    ],
                    "Car Tools & Maintenance": [
                        "Mechanical Tools", "Battery Chargers", "Car Jacks", "Air Compressors", "Diagnostic Tools"
                    ],
                    "Perfumes & Fragrances": [
                        "Men Perfumes", "Women Perfumes", "Body Mists", "Fragrance Oils", "Gift Sets"
                    ],
                    "Fitness & Gym Equipment": [
                        "Dumbbells & Weights", "Treadmills & Running Machines", "Resistance Bands", "Yoga Mats",
                        "Home Gym Machines", "Supplements (Protein, Pre-Workout)", "Water Bottles & Shakers"
                    ],
                    "Women's Fashion": [
                        "Handbags", "Clutches & Wallets", "Shoulder Bags", "Crossbody Bags",
                        "Women Jewelry (Necklaces, Rings, Earrings)", "Scarves & Shawls", "Hair Accessories"
                    ],
                    "Men's Accessories": [
                        "Watches", "Bracelets", "Chains", "Rings", "Sunglasses", "Wallets"
                    ],
                    "Clothing & Apparel": [
                        "Men Clothing (Shirts, Pants, Jackets)", "Women Clothing (Dresses, Tops, Abayas)",
                        "Kids Clothing (Boys, Girls)", "Footwear (Men, Women, Kids)"
                    ],
                    "Mobile Accessories": [
                        "Mobile Covers", "Chargers", "Handsfree & Earphones", "Power Banks", "Screen Protectors"
                    ],
                    "Home & Living": [
                        "Decoration Items", "LED Lights", "Clocks", "Wall Frames", "Artificial Flowers"
                    ],
                    "Gifts & General Items": [
                        "Keychains", "Mugs", "Gift Boxes", "Custom Printed Items", "Souvenirs"
                    ],
                    "Cosmetics": [
                        "Skincare", "Makeup", "Hair Care", "Nail Care", "Body Care", 
                        "Fragrances", "Beauty Tools", "Men's Grooming"
                    ]
                };

                // Global variables
                let totalImages = 5;
                let editingMode = {{ isset($product) && $product ? 'true' : 'false' }};

                // Initialize everything when DOM is loaded
                document.addEventListener('DOMContentLoaded', function() {
                    initializeImageInputs();
                    setupEventListeners();
                    
                    if (editingMode) {
                        autoFillForm();
                    }
                });

                // Setup all event listeners
                function setupEventListeners() {
                    // Word count for description
                    const descriptionTextarea = document.querySelector('textarea[minlength="100"]');
                    if (descriptionTextarea) {
                        descriptionTextarea.addEventListener('input', updateWordCount);
                    }
                    
                    // MRP discount calculation
                    const mrpInput = document.getElementById('mrp');
                    if (mrpInput) {
                        mrpInput.addEventListener('input', calculateMRPDiscount);
                    }
                    
                    // Video upload handling
                    setupVideoUpload();
                    
                    // Add more images button
                    const addMoreBtn = document.getElementById('addMoreImagesBtn');
                    if (addMoreBtn) {
                        addMoreBtn.addEventListener('click', addMoreImages);
                    }
                    
                    // Fault management
                    const addFaultBtn = document.getElementById('addFaultBtn');
                    if (addFaultBtn) {
                        addFaultBtn.addEventListener('click', addFault);
                    }
                    
                    // Form submission
                    const productForm = document.getElementById('productForm');
                    if (productForm) {
                        productForm.addEventListener('submit', handleFormSubmit);
                    }
                    
                    // Category change
                    const mainCategory = document.getElementById('mainCategory');
                    if (mainCategory) {
                        mainCategory.addEventListener('change', updateSubcategories);
                    }
                }

                // Update subcategories based on main category
                function updateSubcategories() {
                    const mainCategory = document.getElementById("mainCategory");
                    const subCategory = document.getElementById("subCategory");
                    const modelLabel = document.getElementById("modelLabel");
                    const dynamicInputContainer = document.getElementById("dynamicInputContainer");

                    if (!mainCategory) return;

                    // Clear subcategories
                    subCategory.innerHTML = '<option value="">Select subcategory</option>';

                    // Label logic and dynamic input
                    if (!mainCategory.value) {
                        modelLabel.textContent = "------";
                        dynamicInputContainer.innerHTML = `
                            <input type="text" name="model" placeholder="Enter value" required class="form-input" />
                        `;
                    } else {
                        const categoryMap = {
                            "Auto Parts & Accessories": "Model",
                            "Car Tools & Maintenance": "Model", 
                            "Mobile Accessories": "Model",
                            "Perfumes & Fragrances": "ML",
                            "Fitness & Gym Equipment": "Specifications"
                        };

                        const label = categoryMap[mainCategory.value] || "Size";
                        modelLabel.textContent = label;

                        if (mainCategory.value === "Fitness & Gym Equipment") {
                            dynamicInputContainer.innerHTML = `
                                <div class="dynamic-input-container">
                                    <input type="text" name="model" placeholder="Enter value" required class="form-input" />
                                    <select name="model_unit" class="form-select" required>
                                        <option value="">Select unit</option>
                                        <option value="weight">Weight</option>
                                        <option value="size">Size</option>
                                        <option value="capacity">Capacity</option>
                                    </select>
                                </div>
                            `;
                        } else {
                            const placeholder = label === "ML" ? "Enter ML (e.g. 50ml, 100ml)" : 
                                            label === "Model" ? "Enter model" : "Enter size (e.g. S, M, L, XL)";
                            
                            dynamicInputContainer.innerHTML = `
                                <input type="text" name="model" placeholder="${placeholder}" required class="form-input" />
                            `;
                        }
                    }

                    // Populate subcategories
                    if (mainCategory.value && subcategories[mainCategory.value]) {
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

                // Initialize image inputs
                function initializeImageInputs() {
                    const requiredContainer = document.getElementById('requiredImagesContainer');
                    if (!requiredContainer) return;

                    const imageInputTemplate = document.getElementById('imageInputTemplate').content;
                    
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
                        
                        // ONLY set required if NOT in edit mode
                        if (!editingMode) {
                            input.required = true;
                        }
                        
                        input.name = `productImages[]`;
                        input.dataset.index = i;
                        input.addEventListener('change', handleImageUpload);
                        
                        containerDiv.appendChild(statusDiv);
                        containerDiv.appendChild(clone);
                        requiredContainer.appendChild(containerDiv);
                    }
                }

                // Handle image upload
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
                    
                    // Style the file upload area
                    fileUploadLabel.classList.add('has-image');
                    e.target.style.opacity = '0.5';
                    e.target.style.pointerEvents = 'none';
                    
                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        createImagePreview(event.target.result, inputIndex, container);
                    };
                    reader.readAsDataURL(file);
                }

                // Create image preview
                function createImagePreview(imageSrc, inputIndex, container) {
                    const previewContainer = document.getElementById('imagePreviews');
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview';
                    previewDiv.dataset.inputIndex = inputIndex;
                    previewDiv.innerHTML = `
                        <img src="${imageSrc}" alt="Preview" />
                        <div class="remove-image" title="Remove image">
                            <i class="fas fa-times"></i>
                        </div>
                    `;
                    
                    // Add remove functionality
                    previewDiv.querySelector('.remove-image').addEventListener('click', function() {
                        removeImage(container, previewDiv);
                    });
                    
                    previewContainer.appendChild(previewDiv);
                    validateImageCount();
                }

                // Remove image
                function removeImage(container, previewDiv) {
                    const input = container.querySelector('input');
                    const statusIndicator = container.querySelector('.upload-status');
                    const fileUploadLabel = container.querySelector('.file-upload-label');
                    
                    // Re-enable the input
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
                    validateImageCount();
                }

                // Add more images
                function addMoreImages() {
                    if (totalImages >= 10) return;
                    
                    const container = document.getElementById('additionalImagesContainer');
                    if (!container) return;

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
                    
                    containerDiv.appendChild(statusDiv);
                    containerDiv.appendChild(clone);
                    container.appendChild(containerDiv);
                    
                    totalImages++;
                    
                    // Hide button if max reached
                    if (totalImages >= 10) {
                        document.getElementById('addMoreContainer').classList.add('hidden');
                    }
                }

                // Validate image count
                function validateImageCount() {
                    const fileInputs = document.querySelectorAll('input[name="productImages[]"]');
                    let filledInputs = 0;
                    
                    fileInputs.forEach(input => {
                        if (input.files.length > 0) {
                            filledInputs++;
                        }
                    });
                    
                    // Count existing images in edit mode
                    const existingImages = document.querySelectorAll('.image-preview[data-existing="true"]').length;
                    const totalImages = filledInputs + existingImages;
                    
                    const submitBtn = document.querySelector('#productForm button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = totalImages < 5;
                        
                        // Show validation message
                        if (totalImages < 5) {
                            showValidationMessage(`Please upload at least ${5 - totalImages} more image(s)`);
                        } else {
                            hideValidationMessage();
                        }
                    }
                    
                    return totalImages >= 5;
                }

                // Show validation message
                function showValidationMessage(message) {
                    let validationDiv = document.getElementById('imageValidationMessage');
                    if (!validationDiv) {
                        validationDiv = document.createElement('div');
                        validationDiv.id = 'imageValidationMessage';
                        validationDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mt-4';
                        
                        const imagesSection = document.querySelector('.form-section');
                        imagesSection.appendChild(validationDiv);
                    }
                    validationDiv.innerHTML = `<strong>Validation Error:</strong> ${message}`;
                    validationDiv.style.display = 'block';
                }

                // Hide validation message
                function hideValidationMessage() {
                    const validationDiv = document.getElementById('imageValidationMessage');
                    if (validationDiv) {
                        validationDiv.style.display = 'none';
                    }
                }

                // Setup video upload
                function setupVideoUpload() {
                    const videoUpload = document.getElementById('videoUpload');
                    const videoPreview = document.getElementById('videoPreview');
                    const videoPreviewElement = videoPreview?.querySelector('video');
                    const removeVideoBtn = videoPreview?.querySelector('.remove-video');
                    const videoUploadLabel = document.getElementById('videoUploadLabel');

                    if (!videoUpload || !videoPreview) return;

                    videoUpload.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        
                        if (file.size > 50 * 1024 * 1024) {
                            alert('Video file size must be less than 50MB');
                            this.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            videoPreviewElement.src = event.target.result;
                            videoPreview.classList.remove('hidden');
                            if (videoUploadLabel) videoUploadLabel.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    });

                    if (removeVideoBtn) {
                        removeVideoBtn.addEventListener('click', function() {
                            videoUpload.value = '';
                            videoPreviewElement.src = '';
                            videoPreview.classList.add('hidden');
                            if (videoUploadLabel) videoUploadLabel.style.display = 'flex';
                        });
                    }
                }

                // Add fault
                function addFault() {
                    const template = document.getElementById('faultTemplate');
                    const container = document.getElementById('faultsContainer');
                    
                    if (!template || !container) return;

                    const clone = document.importNode(template.content, true);
                    const fileInput = clone.querySelector('.file-upload-input');
                    const descriptionTextarea = clone.querySelector('textarea');

                    // Image upload preview
                    fileInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        const label = this.closest('.file-upload').querySelector('.file-upload-label');
                        const reader = new FileReader();

                        reader.onload = function(event) {
                            label.innerHTML = `
                                <img src="${event.target.result}" class="w-full h-full object-cover rounded" alt="Preview" />
                            `;
                        };
                        reader.readAsDataURL(file);
                    });

                    // Remove fault
                    clone.querySelector('.remove-fault').addEventListener('click', function() {
                        this.closest('.fault-item').remove();
                    });

                    container.appendChild(clone);
                }

                // Update word count
                function updateWordCount() {
                    const textarea = this;
                    const wordCount = textarea.value.trim().split(/\s+/).length;
                    const wordCountElement = document.getElementById('wordCount');
                    if (wordCountElement) {
                        wordCountElement.textContent = wordCount;
                    }
                }

                // Calculate MRP discount
                function calculateMRPDiscount() {
                    const sP = document.getElementById('s-pri');
                    const mrp = document.getElementById('mrp');
                    const mrpD = document.getElementById('mrp-dis');
                    
                    if (!sP || !mrp || !mrpD) return;

                    if (sP.value && mrp.value) {
                        const dis = Math.floor((sP.value - mrp.value) / sP.value * 100);
                        mrpD.innerHTML = dis;
                    } else {
                        mrpD.innerHTML = 0;
                    }
                }

                // Handle form submission
                function handleFormSubmit(e) {
                    if (!validateImageCount()) {
                        e.preventDefault();
                        const totalImages = document.querySelectorAll('input[name="productImages[]"]:valid').length + 
                                        document.querySelectorAll('.image-preview[data-existing="true"]').length;
                        const needed = 5 - totalImages;
                        alert(`Please upload at least ${needed} more image(s) or keep existing images`);
                        return false;
                    }
                    
                    // Remove required attributes from file inputs in edit mode to prevent HTML5 validation
                    if (editingMode) {
                        const fileInputs = document.querySelectorAll('input[name="productImages[]"]');
                        fileInputs.forEach(input => {
                            input.required = false;
                        });
                    }
                    
                    // Collect fault descriptions
                    const faultDescriptions = [];
                    document.querySelectorAll('.fault-item textarea').forEach(textarea => {
                        if (textarea.value.trim()) {
                            faultDescriptions.push(textarea.value);
                        }
                    });
                    
                    // Add hidden inputs for fault descriptions
                    faultDescriptions.forEach((desc, index) => {
                        const descInput = document.createElement('input');
                        descInput.type = 'hidden';
                        descInput.name = `fault_descriptions[${index}]`;
                        descInput.value = desc;
                        this.appendChild(descInput);
                    });
                    
                    return true;
                }

                // Auto-fill form in edit mode
                function autoFillForm() {
                    @if(isset($product) && $product)
                        // Fill basic fields
                        const fields = {
                            'product_name': '{{ $product->name }}',
                            'brand': '{{ $product->brand }}',
                            'model': '{{ $product->model }}',
                            'made_in': '{{ $product->made_in }}',
                            'original_price': '{{ $product->original_price }}',
                            'delivery_charges': '{{ $product->delivery_charges }}',
                            'selling_price': '{{ $product->selling_price }}',
                            'mrp': '{{ $product->mrp }}',
                            'quantity': '{{ $product->quantity }}',
                            'shipping_time': '{{ $product->shipping_time }}',
                            'location': '{{ $product->location }}'
                        };

                        Object.keys(fields).forEach(field => {
                            const element = document.querySelector(`[name="${field}"]`);
                            if (element) element.value = fields[field];
                        });
                        console.log(fields);

                        // Fill select fields
                        const selectFields = {
                            'category': '{{ $product->category }}',
                            'condition': '{{ $product->pcondition }}',
                            'shipping_method': '{{ $product->shipping_method }}'
                        };

                        Object.keys(selectFields).forEach(field => {
                            const element = document.querySelector(`select[name="${field}"]`);
                            selectFields[field] = selectFields[field].replace('&amp;', '&');
                            console.log(selectFields[field])
                            if (element) element.value = selectFields[field];
                        });

                        // Fill textareas
                        const textareas = {
                            'description': `{{ $product->description }}`,
                            'return_policy': `{{ $product->return_policy }}`
                        };

                        Object.keys(textareas).forEach(field => {
                            const element = document.querySelector(`textarea[name="${field}"]`);
                            if (element) {
                                element.value = textareas[field];
                                if (field === 'description') {
                                    updateWordCount.call(element);
                                }
                            }
                        });

                        // Update subcategories and set value
                        updateSubcategories();
                        setTimeout(() => {
                            const subCategory = document.querySelector('select[name="subcategory"]');
                            if (subCategory) subCategory.value = '{{ $product->subcategory }}';
                        }, 100);

                        // Calculate MRP discount
                        calculateMRPDiscount();

                        // Load product images
                        @if(isset($productImages) && count($productImages) > 0)
                            let imageIndex = 0;
                            @foreach($productImages as $image)
                                if (imageIndex < 5) {
                                    loadExistingImage('{{ $image->image_path }}', imageIndex);
                                }
                                imageIndex++;
                            @endforeach
                        @endif

                         // Load product video if exists
                        @if($product->video)
                            loadExistingVideo('{{ $product->video }}');
                        @endif

                        // Load product faults
                        @if(isset($productFaults) && count($productFaults) > 0)
                            @foreach($productFaults as $fault)
                                loadExistingFault('{{ $fault->fault_description }}', '{{ $fault->fault_image }}');
                            @endforeach
                        @endif

                        // Update form for edit mode
                        const form = document.getElementById('productForm');
                        if (form) {
                            form.action = '{{ route("vendor.products.update", $product->id) }}';
                            
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'PUT';
                            form.appendChild(methodInput);

                            const submitBtn = form.querySelector('button[type="submit"]');
                            if (submitBtn) submitBtn.textContent = 'Update Product';
                        }
                    @endif
                }

                // Load existing image
                function loadExistingImage(imagePath, index) {
                    const requiredContainer = document.getElementById('requiredImagesContainer');
                    const requiredInputs = requiredContainer.querySelectorAll('input[type="file"]');
                    
                    if (requiredInputs[index]) {
                        const container = requiredInputs[index].closest('.image-input-container');
                        const statusIndicator = container.querySelector('.upload-status');
                        const fileUploadLabel = container.querySelector('.file-upload-label');
                        
                        // Remove required attribute for existing images
                        requiredInputs[index].required = false;
                        
                        // Update status indicator
                        statusIndicator.className = 'upload-status status-filled';
                        statusIndicator.innerHTML = '<i class="fas fa-check" style="font-size: 12px;"></i>';
                        
                        // Style the file upload area
                        fileUploadLabel.classList.add('has-image');
                        fileUploadLabel.innerHTML = `<img src="{{ asset('storage/vendor/products/images/') }}/${imagePath}" class="w-full h-full object-cover rounded" alt="Preview" />`;
                        
                        // Disable input
                        requiredInputs[index].style.opacity = '0.5';
                        requiredInputs[index].style.pointerEvents = 'none';
                        
                        // Add hidden input for existing image
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'existing_images[]';
                        hiddenInput.value = imagePath;
                        container.appendChild(hiddenInput);
                        
                        // Add to preview with existing flag
                        const previewContainer = document.getElementById('imagePreviews');
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'image-preview';
                        previewDiv.dataset.inputIndex = index;
                        previewDiv.dataset.existing = 'true';
                        previewDiv.innerHTML = `
                            <img src="{{ asset('storage/vendor/products/images/') }}/${imagePath}" alt="Preview" />
                            <div class="remove-image" title="Remove image">
                                <i class="fas fa-times"></i>
                            </div>
                        `;
                        
                        // Update remove functionality for existing images
                        previewDiv.querySelector('.remove-image').addEventListener('click', function() {
                            // Remove the hidden input
                            hiddenInput.remove();
                            
                            // Remove the existing flag
                            previewDiv.dataset.existing = 'false';
                            
                            // Re-enable the file input and make it required
                            requiredInputs[index].style.opacity = '1';
                            requiredInputs[index].style.pointerEvents = 'auto';
                            requiredInputs[index].required = true;
                            requiredInputs[index].value = '';
                            
                            // Reset status indicator
                            statusIndicator.className = 'upload-status status-empty';
                            statusIndicator.innerHTML = '<i class="fas fa-plus" style="font-size: 12px;"></i>';
                            
                            // Remove has-image class and image
                            fileUploadLabel.classList.remove('has-image');
                            fileUploadLabel.innerHTML = `
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload</span>
                            `;
                            
                            // Remove preview
                            previewDiv.remove();
                            validateImageCount();
                        });
                        
                        previewContainer.appendChild(previewDiv);
                    }
                    
                    // Re-validate after loading existing images
                    validateImageCount();
                }

                // Load existing video
                function loadExistingVideo(videoPath) {
                    const videoUpload = document.getElementById('videoUpload');
                    const videoPreview = document.getElementById('videoPreview');
                    const videoPreviewElement = videoPreview?.querySelector('video');
                    const videoUploadLabel = document.getElementById('videoUploadLabel');
                    
                    if (!videoUpload || !videoPreview || !videoPreviewElement) return;
                    
                    // Set video source
                    videoPreviewElement.src = "{{ asset('storage/vendor/products/videos/') }}/" + videoPath;
                    videoPreview.classList.remove('hidden');
                    
                    // Hide upload label
                    if (videoUploadLabel) {
                        videoUploadLabel.style.display = 'none';
                    }
                    
                    // Disable the file input to prevent accidental changes
                    videoUpload.disabled = true;
                    videoUpload.style.opacity = '0.5';
                }

                // Load existing fault
                function loadExistingFault(description, imagePath) {
                    const template = document.getElementById('faultTemplate');
                    const container = document.getElementById('faultsContainer');
                    
                    if (!template || !container) return;

                    const clone = document.importNode(template.content, true);
                    
                    // Set fault description
                    const descriptionTextarea = clone.querySelector('textarea');
                    descriptionTextarea.value = description;

                    // Set fault image if exists
                    if (imagePath) {
                        const label = clone.querySelector('.file-upload-label');
                        label.innerHTML = `<img src="{{ asset('storage/vendor/products/faults/') }}/${imagePath}" class="w-full h-full object-cover rounded" alt="Preview" />`;
                    }

                    // Add event listeners
                    const fileInput = clone.querySelector('.file-upload-input');
                    fileInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        const label = this.closest('.file-upload').querySelector('.file-upload-label');
                        const reader = new FileReader();

                        reader.onload = function(event) {
                            label.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover rounded" alt="Preview" />`;
                        };
                        reader.readAsDataURL(file);
                    });

                    clone.querySelector('.remove-fault').addEventListener('click', function() {
                        this.closest('.fault-item').remove();
                    });

                    container.appendChild(clone);
                }
            </script>
        </main>
    </div>
</body>
</html>