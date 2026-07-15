<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/page-loader.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Auto Parts Product</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #E85D85;
            --primary-dark: #C94A72;
            --secondary: #6b7280;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f9fafb;
            --dark: #1f2937;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFF6F0;
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Image Upload Styling */
        .image-upload-container {
            position: relative;
            border-radius: 0.75rem;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 150px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .image-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #cbd5e1;
            border-radius: 0.75rem;
            padding: 1.25rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            background-color: transparent;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .image-upload-label:hover {
            border-color: var(--primary);
            background-color: rgba(59, 130, 246, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .image-upload-label.has-image {
            border-color: var(--success);
            background-color: rgba(16, 185, 129, 0.05);
            border-style: solid;
        }

        .upload-icon {
            font-size: 2rem;
            color: #94a3b8;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .image-upload-label:hover .upload-icon {
            color: var(--primary);
            transform: scale(1.1);
        }

        .upload-text {
            color: #64748b;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .image-count {
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--primary);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 5;
        }

        .image-preview-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 0.75rem;
            overflow: hidden;
            z-index: 2;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .image-preview-container:hover .image-preview {
            transform: scale(1.05);
        }

        .remove-image-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .image-upload-container:hover .remove-image-btn {
            opacity: 1;
            transform: scale(1);
        }

        .remove-image-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 15;
        }

        /* Form Section Styling */
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

        .form-input.has-error:focus, .form-select.has-error:focus, .form-textarea.has-error:focus {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Video Upload Styling */
        .video-upload-container {
            position: relative;
            margin-bottom: 1rem;
        }

        .file-upload {
            position: relative;
        }

        .file-upload-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }

        .video-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed #cbd5e1;
            border-radius: 0.75rem;
            background-color: #f8fafc;
            color: #64748b;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 180px;
        }

        .video-upload-label:hover {
            border-color: var(--primary);
            background-color: rgba(59, 130, 246, 0.05);
            transform: translateY(-2px);
        }

        .video-upload-label i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .video-upload-label:hover i {
            color: var(--primary);
            transform: scale(1.1);
        }

        .video-preview {
            position: relative;
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: #000;
            margin-top: 1rem;
            display: none;
        }

        .video-preview.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .video-preview video {
            width: 100%;
            height: auto;
            max-height: 400px;
            display: block;
            border-radius: 0.75rem;
        }

        .remove-video {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .remove-video:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        /* Preview Images Grid */
        .preview-images-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .preview-image {
            aspect-ratio: 1;
            border-radius: 0.5rem;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .preview-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-image-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #94a3b8;
        }

        .preview-image-count {
            position: absolute;
            bottom: 4px;
            right: 4px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            font-size: 0.625rem;
            padding: 2px 6px;
            border-radius: 0.25rem;
        }

        /* Fault Item Styling */
        .fault-item {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.5rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Notification Styling */
        #successNotification, #errorNotification {
            animation: slideIn 0.3s ease-out;
            z-index: 9999;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Spinner */
        .spinner-small {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Auto Parts Specific */
        .part-type-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .category-highlight {
            border-left: 4px solid var(--primary);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            :user="$user"
            page='Dashboard'
        />

        <main class="flex-1 p-6 overflow-y-auto scrollbar-hide">
            <!-- Notifications -->
            <div id="successNotification" class="fixed top-4 right-4 z-50 max-w-md hidden">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800" id="successTitle">Success!</h3>
                            <div class="mt-1 text-sm text-green-700" id="successContent">
                                <p></p>
                            </div>
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" onclick="hideSuccess()" class="text-green-500 hover:text-green-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="errorNotification" class="fixed top-4 right-4 z-50 max-w-md hidden">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800" id="errorTitle">Error!</h3>
                            <div class="mt-1 text-sm text-red-700" id="errorContent">
                                <p></p>
                            </div>
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" onclick="hideError()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto p-6 text-gray-800 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">Add Auto Parts Product</h1>
                        <span class="part-type-badge">Auto Parts</span>
                    </div>
                    <p class="text-gray-600 mb-6">Fill in the details below to list your auto parts product</p>

                    <form class="space-y-6" id="productForm" action="{{ route('vendor.products.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <!-- Product Images Section -->
                        <div class="form-section">
                            <h2>Product Images</h2>
                            <p class="text-sm text-gray-500 mb-4">Minimum 5, Maximum 10 images required</p>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6" id="requiredImagesContainer"></div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4" id="additionalImagesContainer"></div>
                            
                            <div id="addMoreContainer" class="text-center">
                                <button type="button" id="addMoreImagesBtn" class="btn-primary">
                                    <i class="fas fa-plus mr-2"></i> Add More Images
                                </button>
                                <p class="text-sm text-gray-500 mt-2">You can add up to 10 images total</p>
                            </div>
                        </div>

                        <!-- Product Video Section -->
                        <div class="form-section">
                            <h2>Product Video</h2>
                            <p class="text-sm text-gray-500 mb-4">Upload a video showcasing your auto part</p>
                            
                            <div class="video-upload-container">
                                <div class="file-upload">
                                    <input type="file" accept="video/*" class="file-upload-input" id="videoUpload" name="product_video" />
                                    <label for="videoUpload" class="video-upload-label" id="videoUploadLabel">
                                        <i class="fas fa-video"></i>
                                        <span>Click to upload product video</span>
                                        <p class="text-xs mt-2">MP4, MOV, AVI (Max 50MB)</p>
                                    </label>
                                </div>
                                
                                <div id="videoPreview" class="video-preview">
                                    <div class="video-loading hidden" id="videoLoading">
                                        <div class="spinner-small" style="width: 50px; height: 50px;"></div>
                                    </div>
                                    <video controls></video>
                                    <div class="remove-video" title="Remove video">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Name -->
                        <div class="form-section">
                            <label class="form-label">Product Name*</label>
                            <input type="text" id="productName" name="product_name" placeholder="e.g., Front Bumper for Honda Civic 2020" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                            <div class="error-message hidden" id="productNameError"></div>
                        </div>

                        <!-- Category Selection -->
                        <div class="form-section">
                            <h2>Category & Part Type</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Main Category -->
                                <div>
                                    <label class="form-label">Category*</label>
                                    <select name="category" id="mainCategory" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                                        <option value="">Select category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error-message hidden" id="categoryError"></div>
                                </div>

                                <!-- Subcategory -->
                                <div>
                                    <label class="form-label">Subcategory*</label>
                                    <select name="subcategory" id="subCategory" required disabled class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select bg-gray-100">
                                        <option value="">First select a category</option>
                                    </select>
                                    <div class="error-message hidden" id="subcategoryError"></div>
                                </div>
                            </div>

                            <!-- Part Type Dropdown (Dynamic) -->
                            <div class="mt-4 hidden" id="partTypeContainer">
                                <label class="form-label">Part Type*</label>
                                <select name="part_type" id="partType" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                                    <option value="">Select part type</option>
                                </select>
                                <div class="error-message hidden" id="partTypeError"></div>
                            </div>
                        </div>

                        <!-- Brand, Model & Made In -->
                        <div class="form-section">
                            <h2>Product Details</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="form-label">Brand*</label>
                                    <input type="text" id="brand" name="brand" placeholder="e.g., Honda, Toyota, Ford" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                                    <div class="error-message hidden" id="brandError"></div>
                                </div>
                                <div>
                                    <label class="form-label">Model/Compatibility*</label>
                                    <input type="text" id="model" name="model" placeholder="e.g., Civic 2020, Corolla 2019" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                                    <div class="error-message hidden" id="modelError"></div>
                                </div>
                                <div>
                                    <label class="form-label">Made In*</label>
                                    <input type="text" id="madeIn" name="made_in" placeholder="Country of origin" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                                    <div class="error-message hidden" id="madeInError"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Condition -->
                        <div class="form-section">
                            <label class="form-label">Condition*</label>
                            <select name="condition" id="condition" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select" required>
                                <option value="">Select condition</option>
                                <option value="New">New</option>
                                <option value="Used">Used</option>
                                <option value="Refurbished">Refurbished</option>
                            </select>
                            <div class="error-message hidden" id="conditionError"></div>
                        </div>

                        <!-- Price & Quantity -->
                        <div class="form-section">
                            <h2>Pricing & Stock</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="form-label">Selling Price (PKR)*</label>
                                    <input type="number" id="sellingPrice" name="selling_price" placeholder="0.00" min="1" required step="0.01"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                                    <div class="error-message hidden" id="sellingPriceError"></div>
                                </div>

                                <div>
                                    <label class="form-label">MRP (PKR) - Optional</label>
                                    <input type="number" id="mrp" name="mrp" placeholder="0.00" min="0" step="0.01"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                                    <div class="error-message hidden" id="mrpError"></div>
                                </div>
                                
                                <!-- Delivery Charges -->
                                <div>
                                    <label class="form-label">Delivery Charges*</label>
                                    <input type="number" id="deliveryCharges" name="delivery_charges" value="250" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input readonly-input" />
                                    <div class="error-message hidden" id="deliveryChargesError"></div>
                                </div>

                                <div>
                                    <label class="form-label">Quantity in Stock*</label>
                                    <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" min="1" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                                    <div class="error-message hidden" id="quantityError"></div>
                                </div>
                            </div>

                            <!-- GST Calculation -->
                            <div class="mt-6 p-4 bg-pink-50 border border-pink-200 rounded-md">
                                <h4 class="text-[#C94A72] font-semibold mb-2 flex items-center">
                                    <i class="fas fa-percentage mr-2"></i> GST Calculation (17%)
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-[#E85D85]">Selling Price:</span>
                                        <span class="text-[#C94A72] font-medium" id="gstSellingPrice">PKR 0.00</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-[#E85D85]">GST (17%):</span>
                                        <span class="text-[#C94A72] font-medium" id="gstAmount">PKR 0.00</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-pink-200">
                                        <span class="text-sm font-semibold text-[#C94A72]">Total with GST:</span>
                                        <span class="text-[#C94A72] font-bold" id="gstTotal">PKR 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping -->
                        <div class="form-section">
                            <h2>Shipping Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Shipping Method*</label>
                                    <select name="shipping_method" id="shippingMethod" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select" required>
                                        <option value="">Select shipping method</option>
                                        <option value="standard">Standard</option>
                                        <option value="express">Express</option>
                                        <option value="local">Local Pickup</option>
                                    </select>
                                    <div class="error-message hidden" id="shippingMethodError"></div>
                                </div>
                                <div>
                                    <label class="form-label">Shipping Time*</label>
                                    <input type="text" id="shippingTime" name="shipping_time" placeholder="e.g., 3-5 business days" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                                    <div class="error-message hidden" id="shippingTimeError"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Full Description -->
                        <div class="form-section">
                            <label class="form-label">Description (Minimum 100 words)*</label>
                            <textarea id="description" name="description" placeholder="Detailed description including compatibility, material, dimensions, etc." required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-textarea h-32 resize-none"></textarea>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-sm text-gray-500">Word count: <span id="wordCount">0</span>/100</p>
                                <div class="error-message hidden" id="descriptionError"></div>
                            </div>
                        </div>

                        <!-- Return Policy -->
                        <div class="form-section">
                            <h2>Return Policy</h2>
                            <div class="return-policy bg-pink-50 border-l-4 border-[#E85D85] p-4 rounded">
                                <h3 class="font-semibold mb-2">Standard Auto Parts Return Policy</h3>
                                <p class="text-sm text-gray-600">Parts can be returned within 7 days if unused and in original packaging. Electrical parts cannot be returned once installed. Buyer responsible for return shipping unless item is defective.</p>
                            </div>
                            <div class="mt-4">
                                <label class="form-label">Custom Return Policy (Optional)</label>
                                <textarea id="returnPolicy" name="return_policy" placeholder="Add your custom return policy here"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-textarea h-24 resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Faults Section -->
                        <div class="form-section">
                            <div class="flex justify-between items-center mb-4">
                                <h2>Product Faults (Optional)</h2>
                                <button type="button" id="addFaultBtn" class="btn-primary">
                                    <i class="fas fa-plus mr-2"></i> Add Fault
                                </button>
                            </div>
                            <div id="faultsContainer" class="space-y-4"></div>
                        </div>

                        <!-- Location -->
                        <div class="form-section">
                            <label class="form-label">Location*</label>
                            <input type="text" id="location" name="location" placeholder="City where part is located" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
                            <div class="error-message hidden" id="locationError"></div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-right pt-4">
                            <button type="submit" class="btn-primary px-8 py-3" id="submitBtn">
                                <span id="submitText">Submit Auto Parts Product</span>
                                <div id="submitLoader" class="hidden ml-2 inline-block">
                                    <div class="spinner-small"></div>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Preview Box -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sticky top-6 max-h-[calc(100vh-4rem)] overflow-y-auto preview-box">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 text-center flex items-center justify-center">
                            <i class="fas fa-eye text-[#E85D85] mr-2"></i> Live Preview
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="preview-images-grid" id="previewImages"></div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-info-circle text-[#E85D85] mr-2"></i>
                                    Product Details
                                </h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Product Name:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewName">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Category:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewCategory">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Part Type:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewPartType">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Brand:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewBrand">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Compatibility:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewModel">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Condition:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewCondition">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Made In:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewMadeIn">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Quantity:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewQuantity">-</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-pink-50 border border-pink-100 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-receipt text-[#E85D85] mr-2"></i>
                                    Price Breakdown
                                </h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Selling Price:</span>
                                        <span class="text-gray-800 font-medium" id="previewSellingPrice">PKR 0.00</span>
                                    </div>
                                    
                                    <div id="previewMRPContainer" class="hidden">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm line-through">MRP:</span>
                                            <span class="text-gray-500 line-through" id="previewMRP">PKR 0.00</span>
                                        </div>
                                    </div>
                                    
                                    <div id="previewDiscountContainer" class="hidden">
                                        <div class="flex justify-between items-center bg-green-50 px-2 py-1 rounded">
                                            <span class="text-green-600 text-sm font-medium">You Save:</span>
                                            <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-bold" id="previewDiscount">0% OFF</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center bg-pink-100 px-2 py-1 rounded">
                                        <div class="flex items-center">
                                            <span class="text-gray-600 text-sm flex items-center">
                                                <i class="fas fa-percentage text-xs mr-1 text-[#E85D85]"></i>
                                                GST (17%):
                                            </span>
                                        </div>
                                        <span class="text-[#C94A72] font-medium" id="previewGST">PKR 0.00</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center pt-2 border-t border-pink-200 mt-2">
                                        <span class="text-gray-800 font-bold">Total Price:</span>
                                        <span class="text-[#E85D85] font-bold text-lg" id="previewTotalPrice">PKR 0.00</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-shipping-fast text-green-500 mr-2"></i>
                                    Shipping Information
                                </h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Shipping Method:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewShippingMethod">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Shipping Time:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewShippingTime">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Location:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewLocation">-</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-align-left text-purple-500 mr-2"></i>
                                    Description
                                </h3>
                                <div id="previewDescription" class="text-gray-700 text-sm leading-relaxed max-h-32 overflow-y-auto">
                                    <div class="text-gray-500 italic text-center py-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        No description provided
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-sync-alt text-[#E85D85] mr-2 animate-spin"></i>
                                    <span class="text-sm">Live Updates</span>
                                </div>
                                <div class="text-xs text-gray-500" id="previewStatus">
                                    0/5 images uploaded
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Image Upload Template -->
    <template id="imageUploadTemplate">
        <div class="image-upload-container" data-index="0">
            <div class="image-count">1</div>
            <input type="file" accept="image/*" class="file-input" name="productImages[]" />
            <div class="image-upload-label">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <span class="upload-text">Click to upload</span>
            </div>
            <div class="image-preview-container" style="display: none;">
                <img class="image-preview" alt="Preview" />
                <div class="remove-image-btn" title="Remove image">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        </div>
    </template>

    <!-- Fault Template -->
    <template id="faultTemplate">
        <div class="fault-item">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-1/3">
                    <label class="form-label flex items-center gap-2 mb-2">
                        <i class="fas fa-camera text-[#E85D85]"></i>
                        Fault Image
                    </label>
                    <div class="image-upload-container" style="height: 120px;">
                        <input type="file" accept="image/*" class="file-input" name="faults[]" />
                        <div class="image-upload-label">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <span class="upload-text text-xs">Click to upload</span>
                        </div>
                        <div class="image-preview-container" style="display: none;">
                            <img class="image-preview" alt="Fault preview" />
                            <div class="remove-image-btn">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-2/3">
                    <label class="form-label flex items-center gap-2 mb-2">
                        <i class="fas fa-align-left text-purple-500"></i>
                        Fault Description
                    </label>
                    <div class="relative">
                        <textarea placeholder="Describe the fault in detail..." 
                                name="fault_descriptions[]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-textarea h-28 resize-none pr-12"></textarea>
                        <div class="absolute bottom-2 right-2 flex items-center gap-2">
                            <div class="text-xs text-gray-400 font-medium">
                                <span class="char-count">0</span>/500
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-3 pt-3 border-t border-gray-100">
                <button type="button" class="remove-fault flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all duration-300 text-sm">
                    <i class="fas fa-trash-alt"></i>
                    <span>Remove Fault</span>
                </button>
            </div>
        </div>
    </template>

    <script>
        // Store subcategories data
        let subcategoriesData = @json($subcategories);
        
        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeImageInputs();
            setupEventListeners();
            setupLivePreview();
            setupVideoUpload();
        });

        // Initialize image inputs
        function initializeImageInputs() {
            const requiredContainer = document.getElementById('requiredImagesContainer');
            if (!requiredContainer) return;

            const template = document.getElementById('imageUploadTemplate').content;
            
            // Create 5 required image inputs
            for (let i = 0; i < 5; i++) {
                const clone = document.importNode(template, true);
                const container = clone.querySelector('.image-upload-container');
                const input = clone.querySelector('input[type="file"]');
                const count = clone.querySelector('.image-count');
                const previewContainer = clone.querySelector('.image-preview-container');
                const previewImg = clone.querySelector('.image-preview');
                const uploadLabel = clone.querySelector('.image-upload-label');
                const removeBtn = clone.querySelector('.remove-image-btn');

                container.dataset.index = i;
                count.textContent = i + 1;
                input.name = `productImages[]`;
                input.dataset.index = i;
                input.required = true;

                // Add event listeners
                input.addEventListener('change', function(e) {
                    handleImageUpload(e, container, previewContainer, previewImg, uploadLabel);
                });

                removeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    removeImage(container, previewContainer, input, uploadLabel);
                });

                requiredContainer.appendChild(clone);
            }
        }

        // Handle image upload
        function handleImageUpload(e, container, previewContainer, previewImg, uploadLabel) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file
            if (!validateImageFile(file)) {
                e.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                previewContainer.style.display = 'block';
                uploadLabel.classList.add('has-image');
                updatePreviewImages();
            };
            reader.readAsDataURL(file);
        }

        // Validate image file
        function validateImageFile(file) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            
            if (!allowedTypes.includes(file.type)) {
                showError('Invalid file type. Please upload JPEG, PNG, or WebP images.');
                return false;
            }
            
            if (file.size > maxSize) {
                showError('Image size must be less than 5MB.');
                return false;
            }
            
            return true;
        }

        // Remove image
        function removeImage(container, previewContainer, input, uploadLabel) {
            input.value = '';
            previewContainer.style.display = 'none';
            uploadLabel.classList.remove('has-image');
            updatePreviewImages();
        }

        // Add more images
        document.getElementById('addMoreImagesBtn')?.addEventListener('click', function() {
            const container = document.getElementById('additionalImagesContainer');
            if (!container) return;

            const currentImages = document.querySelectorAll('input[name="productImages[]"]').length;
            if (currentImages >= 10) {
                showError('Maximum 10 images allowed');
                return;
            }

            const template = document.getElementById('imageUploadTemplate').content;
            const clone = document.importNode(template, true);
            const imageContainer = clone.querySelector('.image-upload-container');
            const input = clone.querySelector('input[type="file"]');
            const count = clone.querySelector('.image-count');
            const previewContainer = clone.querySelector('.image-preview-container');
            const previewImg = clone.querySelector('.image-preview');
            const uploadLabel = clone.querySelector('.image-upload-label');
            const removeBtn = clone.querySelector('.remove-image-btn');

            count.textContent = currentImages + 1;
            input.name = `productImages[]`;
            input.required = false;

            input.addEventListener('change', function(e) {
                handleImageUpload(e, imageContainer, previewContainer, previewImg, uploadLabel);
            });

            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeImage(imageContainer, previewContainer, input, uploadLabel);
            });

            container.appendChild(clone);
            
            if (currentImages + 1 >= 10) {
                this.style.display = 'none';
            }
        });

        // Setup video upload
        function setupVideoUpload() {
            const videoUpload = document.getElementById('videoUpload');
            const videoPreview = document.getElementById('videoPreview');
            const videoElement = videoPreview.querySelector('video');
            const removeVideoBtn = videoPreview.querySelector('.remove-video');
            const videoUploadLabel = document.getElementById('videoUploadLabel');
            const videoLoading = document.getElementById('videoLoading');

            videoUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (!validateVideoFile(file)) {
                    this.value = '';
                    return;
                }

                videoLoading.classList.remove('hidden');
                const videoURL = URL.createObjectURL(file);

                videoElement.src = videoURL;
                videoElement.load();

                videoElement.addEventListener('loadeddata', function() {
                    videoLoading.classList.add('hidden');
                    videoPreview.classList.add('active');
                    videoUploadLabel.style.display = 'none';
                });
            });

            removeVideoBtn.addEventListener('click', function() {
                videoUpload.value = '';
                videoPreview.classList.remove('active');
                videoUploadLabel.style.display = 'flex';
                videoElement.src = '';
            });
        }

        // Validate video file
        function validateVideoFile(file) {
            const maxSize = 50 * 1024 * 1024; // 50MB
            const allowedTypes = ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'video/ogg'];
            
            if (!allowedTypes.includes(file.type)) {
                showError('Invalid video format. Please upload MP4, MOV, AVI, WebM, or OGG files.');
                return false;
            }
            
            if (file.size > maxSize) {
                showError('Video size must be less than 50MB.');
                return false;
            }
            
            return true;
        }

        // Setup event listeners
        function setupEventListeners() {
            // Category change
            document.getElementById('mainCategory').addEventListener('change', updateSubcategories);
            
            // Price calculation
            ['sellingPrice', 'mrp'].forEach(id => {
                document.getElementById(id)?.addEventListener('input', calculatePrices);
            });
            
            // Word count
            document.getElementById('description')?.addEventListener('input', updateWordCount);
            
            // Add fault button
            document.getElementById('addFaultBtn')?.addEventListener('click', addFault);
        }

        // Update subcategories based on category selection
        function updateSubcategories() {
            const categoryId = document.getElementById('mainCategory').value;
            const subCategory = document.getElementById('subCategory');
            const partTypeContainer = document.getElementById('partTypeContainer');
            const partTypeSelect = document.getElementById('partType');

            if (!categoryId) {
                subCategory.innerHTML = '<option value="">First select a category</option>';
                subCategory.disabled = true;
                subCategory.classList.add('bg-gray-100');
                partTypeContainer.classList.add('hidden');
                return;
            }

            // Filter subcategories for selected category
            // const filtered = subcategoriesData.filter(s => s.category_id == categoryId);
            const filtered = subcategoriesData;
            
            subCategory.innerHTML = '<option value="">Select subcategory</option>';
            filtered.forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.subcategory_name;
                option.textContent = sub.subcategory_name;
                option.dataset.dropdownType = sub.dropdown_type;
                subCategory.appendChild(option);
            });
            
            subCategory.disabled = false;
            subCategory.classList.remove('bg-gray-100');
            
            // Hide part type container initially
            partTypeContainer.classList.add('hidden');
        }

        // Handle subcategory change
        document.getElementById('subCategory')?.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const dropdownType = selectedOption.dataset.dropdownType;
            const partTypeContainer = document.getElementById('partTypeContainer');
            const partTypeSelect = document.getElementById('partType');

            if (dropdownType) {
                // Split dropdown type by commas and create options
                const types = dropdownType.split(',').map(t => t.trim());
                
                partTypeSelect.innerHTML = '<option value="">Select part type</option>';
                types.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = type;
                    partTypeSelect.appendChild(option);
                });
                
                partTypeContainer.classList.remove('hidden');
            } else {
                partTypeContainer.classList.add('hidden');
            }
        });

        // Calculate GST and update price preview
        function calculatePrices() {
            const sellingPrice = parseFloat(document.getElementById('sellingPrice')?.value) || 0;
            const mrp = parseFloat(document.getElementById('mrp')?.value) || 0;
            
            const gstAmount = sellingPrice * 0.17;
            const sellingPriceWithGST = sellingPrice + gstAmount;
            const totalPrice = sellingPriceWithGST; // No delivery charges in total

            document.getElementById('gstSellingPrice').textContent = formatCurrency(sellingPrice);
            document.getElementById('gstAmount').textContent = formatCurrency(gstAmount);
            document.getElementById('gstTotal').textContent = formatCurrency(sellingPriceWithGST);

            // Update preview
            document.getElementById('previewSellingPrice').textContent = formatCurrency(sellingPrice);
            document.getElementById('previewGST').textContent = formatCurrency(gstAmount);
            document.getElementById('previewTotalPrice').textContent = formatCurrency(totalPrice);

            // Handle MRP and discount
            const mrpContainer = document.getElementById('previewMRPContainer');
            const discountContainer = document.getElementById('previewDiscountContainer');
            const previewMRP = document.getElementById('previewMRP');
            const previewDiscount = document.getElementById('previewDiscount');

            if (mrp > 0 && sellingPrice > 0 && mrp > sellingPrice) {
                const discount = ((mrp - sellingPriceWithGST) / mrp) * 100;
                
                mrpContainer.classList.remove('hidden');
                discountContainer.classList.remove('hidden');
                previewMRP.textContent = formatCurrency(mrp);
                previewDiscount.textContent = `${discount.toFixed(1)}% OFF`;
            } else {
                mrpContainer.classList.add('hidden');
                discountContainer.classList.add('hidden');
            }
        }

        // Update word count
        function updateWordCount() {
            const textarea = this;
            const words = textarea.value.trim().split(/\s+/).filter(word => word.length > 0);
            const wordCount = words.length;
            const wordCountElement = document.getElementById('wordCount');
            
            if (wordCountElement) {
                wordCountElement.textContent = wordCount;
                wordCountElement.className = wordCount >= 100 ? 'text-green-600 font-bold' : 
                                        wordCount >= 50 ? 'text-yellow-600' : 'text-red-600';
            }
            
            updateLivePreview();
        }

        // Add fault
        function addFault() {
            const template = document.getElementById('faultTemplate').content;
            const container = document.getElementById('faultsContainer');
            
            const clone = document.importNode(template, true);
            const imageContainer = clone.querySelector('.image-upload-container');
            const fileInput = clone.querySelector('input[type="file"]');
            const previewContainer = clone.querySelector('.image-preview-container');
            const previewImg = clone.querySelector('.image-preview');
            const uploadLabel = clone.querySelector('.image-upload-label');
            const descriptionTextarea = clone.querySelector('textarea');
            const charCount = clone.querySelector('.char-count');
            const removeFaultBtn = clone.querySelector('.remove-fault');

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file || !validateImageFile(file)) {
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    previewContainer.style.display = 'block';
                    uploadLabel.classList.add('has-image');
                };
                reader.readAsDataURL(file);
            });

            descriptionTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;
                charCount.className = count > 400 ? 'text-red-600 font-bold' : 
                                    count > 300 ? 'text-yellow-600' : 'text-gray-600';
            });

            removeFaultBtn.addEventListener('click', function() {
                container.removeChild(this.closest('.fault-item'));
            });

            const removeImageBtn = clone.querySelector('.remove-image-btn');
            removeImageBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.value = '';
                previewContainer.style.display = 'none';
                uploadLabel.classList.remove('has-image');
            });

            container.appendChild(clone);
            descriptionTextarea.dispatchEvent(new Event('input'));
        }

        // Setup live preview
        function setupLivePreview() {
            const form = document.getElementById('productForm');
            if (!form) return;

            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', updateLivePreview);
                input.addEventListener('change', updateLivePreview);
            });

            updateLivePreview();
        }

        // Update live preview
        function updateLivePreview() {
            const getVal = (id) => document.getElementById(id)?.value || '-';
            
            document.getElementById('previewName').textContent = getVal('productName');
            document.getElementById('previewBrand').textContent = getVal('brand');
            document.getElementById('previewModel').textContent = getVal('model');
            document.getElementById('previewMadeIn').textContent = getVal('madeIn');
            document.getElementById('previewCondition').textContent = getVal('condition');
            document.getElementById('previewQuantity').textContent = getVal('quantity');
            document.getElementById('previewShippingMethod').textContent = getVal('shippingMethod');
            document.getElementById('previewShippingTime').textContent = getVal('shippingTime');
            document.getElementById('previewLocation').textContent = getVal('location');

            // Category preview
            const categorySelect = document.getElementById('mainCategory');
            const categoryText = categorySelect.options[categorySelect.selectedIndex]?.text || '-';
            document.getElementById('previewCategory').textContent = categoryText;

            // Part type preview
            const partType = document.getElementById('partType');
            document.getElementById('previewPartType').textContent = partType?.value || '-';

            // Description preview
            const description = document.getElementById('description')?.value || '';
            const previewDescription = document.getElementById('previewDescription');
            
            if (description.trim()) {
                const words = description.trim().split(/\s+/).filter(w => w.length > 0).length;
                const status = words >= 100 ? '✅ ' : words >= 50 ? '⚠️ ' : '❌ ';
                previewDescription.innerHTML = `
                    <div class="text-gray-600 mb-1 text-xs">${status}${words} words</div>
                    <div class="text-gray-700 text-sm">${description.substring(0, 150)}${description.length > 150 ? '...' : ''}</div>
                `;
            } else {
                previewDescription.innerHTML = '<div class="text-gray-500 italic text-center py-2">No description provided</div>';
            }

            calculatePrices();
            updatePreviewImages();
        }

        // Update preview images
        function updatePreviewImages() {
            const previewContainer = document.getElementById('previewImages');
            if (!previewContainer) return;

            const imageInputs = document.querySelectorAll('input[name="productImages[]"]');
            const uploadedImages = Array.from(imageInputs)
                .filter(input => input.files.length > 0)
                .map(input => URL.createObjectURL(input.files[0]));

            previewContainer.innerHTML = '';

            uploadedImages.forEach((imageUrl, index) => {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'preview-image';
                previewDiv.innerHTML = `
                    <img src="${imageUrl}" alt="Product Image ${index + 1}" />
                    <div class="preview-image-count">${index + 1}</div>
                `;
                previewContainer.appendChild(previewDiv);
            });

            const remainingSlots = Math.max(0, 5 - uploadedImages.length);
            for (let i = 0; i < remainingSlots; i++) {
                const placeholder = document.createElement('div');
                placeholder.className = 'preview-image';
                placeholder.innerHTML = `
                    <div class="preview-image-placeholder">
                        <i class="fas fa-image text-gray-300 mb-1"></i>
                        <span class="text-xs text-gray-400">Empty</span>
                    </div>
                `;
                previewContainer.appendChild(placeholder);
            }

            document.getElementById('previewStatus').textContent = 
                `${uploadedImages.length}/5 images uploaded | Live preview active`;
        }

        // Format currency
        function formatCurrency(amount) {
            return 'PKR ' + amount.toLocaleString('en-PK', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Handle form submission
        document.getElementById('productForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            clearErrors();
            
            if (!validateForm()) {
                scrollToFirstError();
                return;
            }
            
            setSubmitButtonLoading(true);

            try {
                const formData = new FormData(this);
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();
                console.log(result);

                if (result.success) {
                    showSuccess(result.message);
                    // setTimeout(() => {
                    //     window.location.href = result.redirect || '/vendor/products';
                    // }, 1500);
                } else {
                    setSubmitButtonLoading(false);
                    
                    if (result.errors) {
                        Object.entries(result.errors).forEach(([field, messages]) => {
                            showFieldError(field, messages[0]);
                        });
                        scrollToFirstError();
                    } else {
                        showError(result.message || 'Failed to submit product.');
                    }
                }
            } catch (error) {
                setSubmitButtonLoading(false);
                showError('Network error. Please try again.');
                console.error('Form submission error:', error);
            }
        });

        // Validate form
        function validateForm() {
            let isValid = true;

            // Required fields
            const requiredFields = [
                'productName', 'mainCategory', 'subCategory', 'brand', 
                'model', 'madeIn', 'condition', 'sellingPrice', 'quantity',
                'shippingMethod', 'shippingTime', 'location', 'description'
            ];

            requiredFields.forEach(fieldId => {
                const element = document.getElementById(fieldId);
                if (element && !element.value.trim()) {
                    const errorId = fieldId + 'Error';
                    const errorElement = document.getElementById(errorId);
                    if (errorElement) {
                        errorElement.innerHTML = '<i class="fas fa-exclamation-circle"></i> This field is required';
                        errorElement.classList.remove('hidden');
                    }
                    element.classList.add('has-error');
                    isValid = false;
                }
            });

            // Image validation
            const imageInputs = document.querySelectorAll('input[name="productImages[]"]');
            const filledImages = Array.from(imageInputs).filter(input => input.files.length > 0).length;
            
            if (filledImages < 5) {
                showError(`Please upload at least 5 product images. Currently have ${filledImages}.`);
                isValid = false;
            }

            // Description word count
            const description = document.getElementById('description')?.value || '';
            const wordCount = description.trim().split(/\s+/).filter(w => w.length > 0).length;
            if (wordCount < 100) {
                showFieldError('description', `Minimum 100 words required (currently ${wordCount})`);
                isValid = false;
            }

            // Price validation
            const sellingPrice = parseFloat(document.getElementById('sellingPrice')?.value) || 0;
            if (sellingPrice <= 0) {
                showFieldError('sellingPrice', 'Selling price must be greater than 0');
                isValid = false;
            }

            // Quantity validation
            const quantity = parseInt(document.getElementById('quantity')?.value) || 0;
            if (quantity < 1) {
                showFieldError('quantity', 'Quantity must be at least 1');
                isValid = false;
            }

            return isValid;
        }

        // Show field error
        function showFieldError(field, message) {
            const fieldId = field.replace('_', '');
            const element = document.getElementById(fieldId);
            if (element) {
                element.classList.add('has-error');
                
                const errorElement = document.getElementById(fieldId + 'Error');
                if (errorElement) {
                    errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                    errorElement.classList.remove('hidden');
                }
            }
        }

        // Clear errors
        function clearErrors() {
            document.querySelectorAll('.has-error').forEach(el => {
                el.classList.remove('has-error');
            });
            
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.add('hidden');
                el.innerHTML = '';
            });
            
            hideError();
        }

        // Scroll to first error
        function scrollToFirstError() {
            const firstError = document.querySelector('.has-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Set submit button loading
        function setSubmitButtonLoading(isLoading) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');
            
            if (submitBtn && submitText && submitLoader) {
                submitBtn.disabled = isLoading;
                submitText.textContent = isLoading ? 'Processing...' : 'Submit Auto Parts Product';
                submitLoader.classList.toggle('hidden', !isLoading);
            }
        }

        // Show error notification
        function showError(message) {
            const notification = document.getElementById('errorNotification');
            const contentElement = document.getElementById('errorContent');
            
            if (notification && contentElement) {
                contentElement.querySelector('p').textContent = message;
                notification.classList.remove('hidden');
                
                setTimeout(hideError, 5000);
            }
        }

        // Hide error notification
        window.hideError = function() {
            document.getElementById('errorNotification')?.classList.add('hidden');
        };

        // Show success notification
        function showSuccess(message) {
            const notification = document.getElementById('successNotification');
            const contentElement = document.getElementById('successContent');
            
            if (notification && contentElement) {
                contentElement.querySelector('p').textContent = message;
                notification.classList.remove('hidden');
            }
        }

        // Hide success notification
        window.hideSuccess = function() {
            document.getElementById('successNotification')?.classList.add('hidden');
        };
    </script>
</body>
</html>