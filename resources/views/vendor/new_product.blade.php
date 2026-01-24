<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($product) ? 'Edit Product' : 'Add New Product' }} | Dynamic Fields</title>
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
            --warning: #f59e0b;
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

        /* Enhanced Image Upload Styling */
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
            animation: successPulse 2s infinite;
        }

        .image-upload-label.has-image:hover {
            border-color: #059669;
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

        .image-upload-label:hover .upload-text {
            color: var(--primary);
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

        /* Progress bar for uploads */
        .upload-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(59, 130, 246, 0.1);
            z-index: 5;
            overflow: hidden;
        }

        .upload-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Error Styling */
        .has-error {
            border-color: var(--danger) !important;
            background-color: rgba(239, 68, 68, 0.05) !important;
        }

        .error-message {
            color: var(--danger);
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            animation: fadeIn 0.3s ease;
        }

        .error-icon {
            color: var(--danger);
            font-size: 0.875rem;
        }

        .form-section.error-section {
            border-left: 4px solid var(--danger);
            animation: errorPulse 2s infinite;
        }

        /* Animation */
        @keyframes successPulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }
            50% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }

        @keyframes errorPulse {
            0%, 100% {
                border-left-color: var(--danger);
            }
            50% {
                border-left-color: #fca5a5;
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

        /* Loading States */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 20;
            border-radius: 0.75rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 32px;
            height: 32px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        /* Return Policy Styling */
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

        .video-upload-label span {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 1.125rem;
        }

        .video-upload-label p {
            font-size: 0.875rem;
            color: #94a3b8;
        }

        /* Video Preview Styling */
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

        /* Video Loading State */
        .video-loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            z-index: 5;
        }

        .video-loading .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .image-upload-container {
                height: 120px;
            }
            
            .preview-images-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .upload-icon {
                font-size: 1.5rem;
            }
            
            .video-upload-label {
                padding: 1.5rem;
                min-height: 150px;
            }
            
            .video-upload-label i {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 640px) {
            .image-upload-container {
                height: 100px;
            }
            
            .preview-images-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .video-upload-label {
                padding: 1rem;
                min-height: 120px;
            }
            
            .video-upload-label i {
                font-size: 2rem;
            }
        }

        /* Spinner for buttons */
        .spinner-small {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        /* GST Calculation Styling */
        .gst-calc {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #bae6fd;
        }

        .gst-calc h4 {
            color: #0369a1;
            font-weight: 600;
        }

        .gst-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Submit Button Specific Styling */
        #submitBtn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }

        #submitBtn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -3px rgba(16, 185, 129, 0.3);
        }

        #submitBtn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Video preview in sidebar */
        #previewVideo video {
            width: 100%;
            height: 180px;
            object-fit: contain;
            background: #000;
            border-radius: 0.5rem;
        }

        /* Existing Images Styling */
        .existing-image .image-preview-container {
            display: block !important;
            position: relative;
        }

        .existing-image .image-upload-label {
            display: none;
        }

        .existing-image .remove-image-btn {
            opacity: 1;
            transform: scale(1);
        }

        .existing-image .remove-image-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }
        
        /* Price Comparison Info */
        .price-info {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fbbf24;
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin-top: 0.5rem;
        }
        
        .price-info .info-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .price-info .info-label {
            color: #92400e;
            font-weight: 500;
        }
        
        .price-info .info-value {
            color: #b45309;
            font-weight: 600;
        }
        
        .price-info .info-note {
            color: #92400e;
            font-size: 0.75rem;
            margin-top: 0.5rem;
            font-style: italic;
        }
        
        /* Readonly input styling */
        .readonly-input {
            background-color: #f3f4f6 !important;
            cursor: not-allowed !important;
            opacity: 0.8;
        }
        
        /* MRP validation message */
        .mrp-validation {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .mrp-validation.valid {
            color: #059669;
        }
        
        .mrp-validation.invalid {
            color: #dc2626;
        }
        
        /* Discount badge */
        .discount-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .discount-badge-large {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 700;
            display: inline-block;
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
            <!-- Success Notification Container -->
            <div id="successNotification" class="fixed top-4 right-4 z-50 max-w-md hidden">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800" id="successTitle"></h3>
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

            <!-- Error Notification Container -->
            <div id="errorNotification" class="fixed top-4 right-4 z-50 max-w-md hidden">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800" id="errorTitle"></h3>
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
                    <h1 class="text-3xl font-bold mb-2 text-gray-900">
                        {{ isset($product) ? 'Edit Product' : 'Add New Product' }}
                    </h1>
                    <p class="text-gray-600 mb-6">
                        {{ isset($product) ? 'Update the product details below' : 'Fill in the details below to list your product' }}
                    </p>

                    <form class="space-y-6" id="productForm" 
                        action="{{ isset($product) ? route('vendor.products.update', $product->id) : route('vendor.products.store') }}" 
                        method="post" enctype="multipart/form-data" novalidate>
                        @csrf
                        
                        @if(isset($product))
                            @method('PUT')
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                        @endif

                        <!-- Existing Images Section (Only in Edit Mode) -->
                        @if(isset($productImages) && count($productImages) > 0)
                        <div class="form-section" id="existingImagesSection">
                            <h2>Existing Product Images</h2>
                            <p class="text-sm text-gray-500 mb-4">Click on the X to remove an image</p>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6" id="existingImagesContainer">
                                @foreach($productImages as $index => $image)
                                <div class="image-upload-container existing-image" data-image-id="{{ $image->id }}">
                                    <div class="image-count">{{ $index + 1 }}</div>
                                    <div class="image-preview-container">
                                        <img class="image-preview" src="{{ asset('storage/vendor/products/images/' . $image->image_path) }}" alt="Existing Image {{ $index + 1 }}" />
                                        <div class="remove-image-btn" title="Remove image" onclick="removeExistingImage(this, {{ $image->id }})">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" name="existing_images[]" value="{{ $image->id }}" class="existing-image-input">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Product Images Section -->
                        <div class="form-section">
                            <h2>Product Images</h2>
                            <p class="text-sm text-gray-500 mb-4">Minimum 5, Maximum 10 images required</p>
                            
                            <!-- Required Images Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6" id="requiredImagesContainer">
                                <!-- 5 required image inputs will be added here -->
                            </div>
                            
                            <!-- Additional Images Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4" id="additionalImagesContainer">
                                <!-- Additional images will be added here -->
                            </div>
                            
                            <!-- Add More Button -->
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
                            <p class="text-sm text-gray-500 mb-4">Upload a video showcasing your product</p>
                            
                            <div class="video-upload-container">
                                <div class="file-upload">
                                    <input type="file" accept="video/*" class="file-upload-input" id="videoUpload" name="productVideo" required />
                                    <label for="videoUpload" class="video-upload-label" id="videoUploadLabel">
                                        <i class="fas fa-video"></i>
                                        <span>Click to upload product video</span>
                                        <p class="text-xs mt-2">MP4, MOV, AVI (Max 50MB)</p>
                                    </label>
                                </div>
                                
                                <!-- Video Preview -->
                                <div id="videoPreview" class="video-preview">
                                    <div class="video-loading hidden" id="videoLoading">
                                        <div class="spinner"></div>
                                    </div>
                                    <video controls>
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="remove-video" title="Remove video">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Existing Video (if any) -->
                            @if(isset($product) && $product->video)
                            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                <p class="text-sm text-blue-700 mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Existing video will be replaced if you upload a new one
                                </p>
                                <input type="hidden" name="existing_video" value="{{ $product->video }}">
                            </div>
                            @endif
                        </div>

                        <!-- Product Name -->
                        <div class="form-section">
                            <label class="form-label">Product Name*</label>
                            <input type="text" id="productName" name="product_name" placeholder="Enter product name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                value="{{ isset($product) ? $product->name : '' }}" />
                            <div class="error-message hidden" id="productNameError"></div>
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
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select"
                                    >
                                        <option value="">Select category</option>
                                        <option value="Auto Parts & Accessories" {{ isset($product) && $product->category == 'Auto Parts & Accessories' ? 'selected' : '' }}>🚗 Auto Parts & Accessories</option>
                                        <option value="Car Tools & Maintenance" {{ isset($product) && $product->category == 'Car Tools & Maintenance' ? 'selected' : '' }}>🛠️ Car Tools & Maintenance</option>
                                        <option value="Perfumes & Fragrances" {{ isset($product) && $product->category == 'Perfumes & Fragrances' ? 'selected' : '' }}>🧴 Perfumes & Fragrances</option>
                                        <option value="Fitness & Gym Equipment" {{ isset($product) && $product->category == 'Fitness & Gym Equipment' ? 'selected' : '' }}>🏋️ Fitness & Gym Equipment</option>
                                        <option value="Women's Fashion" {{ isset($product) && $product->category == 'Women\'s Fashion' ? 'selected' : '' }}>👜 Women's Fashion</option>
                                        <option value="Men's Accessories" {{ isset($product) && $product->category == 'Men\'s Accessories' ? 'selected' : '' }}>👔 Men's Accessories</option>
                                        <option value="Clothing & Apparel" {{ isset($product) && $product->category == 'Clothing & Apparel' ? 'selected' : '' }}>👕 Clothing & Apparel</option>
                                        <option value="Mobile Accessories" {{ isset($product) && $product->category == 'Mobile Accessories' ? 'selected' : '' }}>📱 Mobile Accessories</option>
                                        <option value="Home & Living" {{ isset($product) && $product->category == 'Home & Living' ? 'selected' : '' }}>🏠 Home & Living</option>
                                        <option value="Gifts & General Items" {{ isset($product) && $product->category == 'Gifts & General Items' ? 'selected' : '' }}>🎁 Gifts & General Items</option>
                                        <option value="Cosmetics" {{ isset($product) && $product->category == 'Cosmetics' ? 'selected' : '' }}>💄 Cosmetics</option>
                                    </select>
                                    <div class="error-message hidden" id="categoryError"></div>
                                </div>

                                <!-- Subcategory -->
                                <div>
                                    <label class="form-label">Subcategory*</label>
                                    <select 
                                        name="subcategory" 
                                        id="subCategory"
                                        required
                                        disabled
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select"
                                    >
                                        <option value="">First select a category</option>
                                    </select>
                                    <div class="error-message hidden" id="subcategoryError"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Brand, Model & Made In Section -->
                        <div class="form-section">
                            <h2>Product Details</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="form-label">Brand*</label>
                                    <input type="text" id="brand" name="brand" placeholder="MJ cheezain" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                    value="{{ isset($product) ? $product->brand : '' }}" />
                                    <div class="error-message hidden" id="brandError"></div>
                                </div>
                                <div>
                                    <label id="modelLabel" class="form-label">------</label>
                                    <div id="dynamicInputContainer">
                                        <input type="text" id="model" name="model" placeholder="Enter value" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                            value="{{ isset($product) ? $product->model : '' }}" />
                                    </div>
                                    <div class="error-message hidden" id="modelError"></div>
                                </div>
                                <div>
                                    <label class="form-label">Made In*</label>
                                    <input type="text" id="madeIn" name="made_in" placeholder="Country of origin" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                        value="{{ isset($product) ? $product->made_in : '' }}" />
                                    <div class="error-message hidden" id="madeInError"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Condition -->
                        <div class="form-section">
                            <label class="form-label">Condition*</label>
                            <select name="condition" id="condition" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select" required>
                                <option value="">Select condition</option>
                                <option value="New" {{ isset($product) && $product->pcondition == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Used" {{ isset($product) && $product->pcondition == 'Used' ? 'selected' : '' }}>Used</option>
                                <option value="Refurbished" {{ isset($product) && $product->pcondition == 'Refurbished' ? 'selected' : '' }}>Refurbished</option>
                            </select>
                            <div class="error-message hidden" id="conditionError"></div>
                        </div>

                        <!-- Price & Quantity Section -->
                        <div class="form-section">
                            <h2>Pricing & Stock</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Selling Price -->
                                <div>
                                    <label class="form-label">Selling Price*</label>
                                    <input type="number" id="sellingPrice" name="selling_price" placeholder="PKR" min="1" required step="0.01"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                        value="{{ isset($product) ? $product->selling_price : '' }}" />
                                    <div class="error-message hidden" id="sellingPriceError"></div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i> This is the actual price you want to sell at
                                    </div>
                                </div>

                                <!-- MRP -->
                                <div>
                                    <label class="form-label">MRP (Optional)</label>
                                    <input type="number" id="mrp" name="mrp" placeholder="PKR" min="0" step="0.01"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                        value="{{ isset($product) ? $product->mrp : '' }}" />
                                    <div class="error-message hidden" id="mrpError"></div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-tag mr-1"></i> Display price to show discount (must be higher than selling price)
                                    </div>
                                </div>

                                <!-- Delivery Charges -->
                                <div>
                                    <label class="form-label">Delivery Charges*</label>
                                    <input type="number" id="deliveryCharges" name="delivery_charges" value="250" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input readonly-input" />
                                    <div class="error-message hidden" id="deliveryChargesError"></div>
                                </div>

                                <!-- Quantity -->
                                <div>
                                    <label class="form-label">Quantity in Stock*</label>
                                    <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" min="1" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                        value="{{ isset($product) ? $product->quantity : '' }}" />
                                    <div class="error-message hidden" id="quantityError"></div>
                                </div>
                            </div>
                            
                            <!-- GST Calculation & MRP Validation -->
                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- GST Calculation -->
                                    <div>
                                        <h4 class="text-blue-700 font-semibold mb-2 flex items-center">
                                            <i class="fas fa-percentage mr-2"></i> GST Calculation (17%)
                                        </h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-blue-600">Selling Price:</span>
                                                <span class="text-blue-700 font-medium" id="gstSellingPrice">PKR 0.00</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-blue-600">GST (17%):</span>
                                                <span class="text-blue-700 font-medium" id="gstAmount">PKR 0.00</span>
                                            </div>
                                            <div class="flex justify-between items-center pt-2 border-t border-blue-200">
                                                <span class="text-sm font-semibold text-blue-800">Total with GST:</span>
                                                <span class="text-blue-800 font-bold" id="gstTotal">PKR 0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- MRP Validation & Discount -->
                                    <div>
                                        <h4 class="text-purple-700 font-semibold mb-2 flex items-center">
                                            <i class="fas fa-tag mr-2"></i> MRP & Discount
                                        </h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-purple-600">MRP:</span>
                                                <span class="text-purple-700 font-medium" id="mrpDisplay">PKR 0.00</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-purple-600">Selling Price:</span>
                                                <span class="text-purple-700 font-medium" id="sellingPriceDisplay">PKR 0.00</span>
                                            </div>
                                            <div id="discountCalculation" class="hidden">
                                                <div class="flex justify-between items-center pt-2 border-t border-purple-200">
                                                    <span class="text-sm font-semibold text-purple-800">Discount:</span>
                                                    <span class="discount-badge-large" id="discountPercentage">0%</span>
                                                </div>
                                                <div class="mrp-validation valid mt-2" id="mrpValidation">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <span>MRP is valid for discount display</span>
                                                </div>
                                            </div>
                                            <div id="mrpWarning" class="mrp-validation invalid mt-2 hidden">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                <span id="mrpWarningText"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price Information Note -->
                            <div class="price-info mt-4">
                                <div class="info-item">
                                    <span class="info-label">MRP Rule:</span>
                                    <span class="info-value">MRP ≥ (Selling Price + GST)</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Discount Display:</span>
                                    <span class="info-value">Shown when MRP > Selling Price</span>
                                </div>
                                <div class="info-note">
                                    <i class="fas fa-lightbulb mr-1"></i>
                                    GST is always calculated on selling price for tax purposes. MRP is for display only to show discounts.
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
                                        <option value="standard" {{ isset($product) && $product->shipping_method == 'standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="express" {{ isset($product) && $product->shipping_method == 'express' ? 'selected' : '' }}>Express</option>
                                        <option value="local" {{ isset($product) && $product->shipping_method == 'local' ? 'selected' : '' }}>Local Pickup</option>
                                    </select>
                                    <div class="error-message hidden" id="shippingMethodError"></div>
                                </div>
                                <div>
                                    <label class="form-label">Shipping Time*</label>
                                    <input type="text" id="shippingTime" name="shipping_time" placeholder="e.g. 3-5 business days" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                        value="{{ isset($product) ? $product->shipping_time : '' }}" />
                                    <div class="error-message hidden" id="shippingTimeError"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Full Description -->
                        <div class="form-section">
                            <label class="form-label">Description (Minimum 100 words)*</label>
                            <textarea id="description" name="description" placeholder="Detailed features, size, compatibility (minimum 100 words)" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-textarea h-32 resize-none">{{ isset($product) ? $product->description : '' }}</textarea>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-sm text-gray-500">Word count: <span id="wordCount">0</span>/100</p>
                                <div class="error-message hidden" id="descriptionError"></div>
                            </div>
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
                                <textarea id="returnPolicy" name="return_policy" placeholder="Add your custom return policy here (optional)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-textarea h-24 resize-none">{{ isset($product) ? $product->return_policy : '' }}</textarea>
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
                            <div id="faultsContainer" class="space-y-4">
                                <!-- Faults will be added here dynamically -->
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-section">
                            <label class="form-label">Location*</label>
                            <input type="text" id="location" name="location" placeholder="Vendor shop or city name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" 
                                value="{{ isset($product) ? $product->location : '' }}" />
                            <div class="error-message hidden" id="locationError"></div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-right pt-4">
                            <button type="submit" class="btn-primary px-8 py-3" id="submitBtn">
                                <span id="submitText">{{ isset($product) ? 'Update Product' : 'Submit Product' }}</span>
                                <div id="submitLoader" class="hidden ml-2 inline-block">
                                    <div class="spinner-small"></div>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Preview Box -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 fixed top-6 max-h-[calc(100vh-4rem)] overflow-y-auto preview-box">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 text-center flex items-center justify-center">
                            <i class="fas fa-eye text-blue-500 mr-2"></i> Live Preview
                        </h2>
                        
                        <div class="space-y-4">
                            <!-- Image Preview -->
                            <div class="preview-images-grid" id="previewImages">
                                <!-- Images will be populated here -->
                            </div>
                            
                            <!-- Basic Details -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    Product Details
                                </h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Product Name:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewName">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Brand:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewBrand">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Category:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewCategory">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Condition:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewCondition">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Model/Size:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewModel">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Made In:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewMadeIn">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Location:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewLocation">-</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-gray-600 text-sm font-medium">Quantity:</span>
                                        <span class="text-gray-800 font-medium text-right max-w-[60%]" id="previewQuantity">-</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price Breakdown in Preview Box -->
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-receipt text-blue-500 mr-2"></i>
                                    Price Breakdown
                                </h3>
                                <div class="space-y-2">
                                    <!-- Selling Price -->
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Selling Price:</span>
                                        <span class="text-gray-800 font-medium" id="previewSellingPrice">PKR 0.00</span>
                                    </div>
                                    
                                    <!-- MRP (if exists) -->
                                    <div id="previewMRPContainer" class="hidden">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm line-through">MRP:</span>
                                            <span class="text-gray-500 line-through" id="previewMRP">PKR 0.00</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Discount (if applicable) -->
                                    <div id="previewDiscountContainer" class="hidden">
                                        <div class="flex justify-between items-center bg-green-50 px-2 py-1 rounded">
                                            <span class="text-green-600 text-sm font-medium">You Save:</span>
                                            <span class="discount-badge" id="previewDiscount">0% OFF</span>
                                        </div>
                                    </div>
                                    
                                    <!-- GST -->
                                    <div class="flex justify-between items-center bg-blue-100 px-2 py-1 rounded">
                                        <div class="flex items-center">
                                            <span class="text-gray-600 text-sm flex items-center">
                                                <i class="fas fa-percentage text-xs mr-1 text-blue-600"></i>
                                                GST (17%):
                                            </span>
                                        </div>
                                        <span class="text-blue-700 font-medium" id="previewGST">PKR 0.00</span>
                                    </div>
                                    
                                    <!-- Delivery Charges -->
                                    <div class="flex justify-between items-center hidden">
                                        <span class="text-gray-600 text-sm">Delivery Charges:</span>
                                        <span class="text-gray-800 font-medium" id="previewDelivery">PKR 250.00</span>
                                    </div>
                                    
                                    <!-- Total Price -->
                                    <div class="flex justify-between items-center pt-2 border-t border-blue-200 mt-2">
                                        <span class="text-gray-800 font-bold">Total Price:</span>
                                        <span class="text-blue-600 font-bold text-lg" id="previewTotalPrice">PKR 0.00</span>
                                    </div>
                                    
                                    <!-- Price Summary Note -->
                                    <div class="mt-3 pt-2 border-t border-blue-200">
                                        <p class="text-xs text-blue-600">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Total = Selling Price + 17% GST + Delivery
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Shipping Info -->
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
                                </div>
                            </div>
                            
                            <!-- Description Preview -->
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
                        
                        <!-- Preview Status -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-sync-alt text-blue-500 mr-2 animate-spin"></i>
                                    <span class="text-sm">Live Updates</span>
                                </div>
                                <div class="text-xs text-gray-500" id="previewStatus">
                                    All changes reflected instantly
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
            <div class="loading-overlay">
                <div class="spinner"></div>
            </div>
            <input type="file" accept="image/*" class="file-input" name="productImages[]" />
            <div class="image-upload-label">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <span class="upload-text">Click to upload</span>
                <div class="upload-progress">
                    <div class="upload-progress-bar"></div>
                </div>
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
        <div class="fault-item border border-gray-200 rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition-all duration-300 mb-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Image Section -->
                <div class="w-full md:w-1/3">
                    <label class="form-label flex items-center gap-2 mb-2">
                        <i class="fas fa-camera text-blue-500"></i>
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

                <!-- Description Section -->
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

            <!-- Remove Button -->
            <div class="flex justify-end mt-3 pt-3 border-t border-gray-100">
                <button type="button" class="remove-fault flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all duration-300 text-sm">
                    <i class="fas fa-trash-alt"></i>
                    <span>Remove Fault</span>
                </button>
            </div>
        </div>
    </template>

    <script>
        // Global state
        const state = {
            totalImages: 5,
            maxImages: 10,
            editingMode: {{ isset($product) && $product ? 'true' : 'false' }},
            imagePreviews: [],
            formErrors: {},
            videoFile: null,
            deletedImages: [], // Store IDs of deleted existing images
            deletedVideo: false // Track if existing video was deleted
        };

        // Subcategories data
        const subcategories = {
            "Fitness & Gym Equipment": [
                "Dumbbells & Weights",
                "Barbells & Weight Plates",
                "Kettlebells",
                "Weight Benches",
                "Power Racks & Squat Racks",
                "Treadmills",
                "Exercise Bikes",
                "Cross Trainers / Ellipticals",
                "Rowing Machines",
                "Steppers",
                "Multi Gym Machines",
                "Smith Machines",
                "Cable Machines",
                "Resistance Bands",
                "Battle Ropes",
                "Medicine Balls",
                "Slam Balls",
                "Pull-Up Bars",
                "Push-Up Bars",
                "Ab Rollers",
                "Gym Rings",
                "Yoga Mats",
                "Yoga Blocks",
                "Yoga Straps",
                "Foam Rollers",
                "Punching Bags",
                "Boxing Gloves",
                "Hand Wraps",
                "Skipping Ropes",
                "Gym Gloves",
                "Weightlifting Belts",
                "Wrist / Knee / Elbow Supports",
                "Lifting Straps"
            ],
            "Supplements": [
                "Protein Supplements",
                "Mass Gainers",
                "Creatine",
                "Pre-Workout Supplements",
                "Vitamins & Minerals"
            ],
            "Gym Accessories": [
                "Water Bottles",
                "Shakers",
                "Gym Bags",
                "Gym Towels"
            ],
            // Keep other categories from your existing object
            "Auto Parts & Accessories": [
                "Engine Parts", "Body Parts", "Suspension & Steering", "Brakes & Brake Parts",
                "Car Electronics", "Interior Accessories", "Exterior Accessories", "Tyres & Wheels", "Car Cleaning"
            ],
            "Car Tools & Maintenance": [
                "Mechanical Tools", "Battery Chargers", "Car Jacks", "Air Compressors", "Diagnostic Tools"
            ],
            "Perfumes & Fragrances": [
                "Men Perfumes", "Women Perfumes", "Body Mists", "Fragrance Oils", "Gift Sets"
            ],
            "Women's Fashion": [
                "Handbags", "Clutches & Wallets", "Shoulder Bags", "Crossbody Bags",
                "Women Jewelry", "Scarves & Shawls", "Hair Accessories"
            ],
            "Men's Accessories": [
                "Watches", "Bracelets", "Chains", "Rings", "Sunglasses", "Wallets"
            ],
            "Clothing & Apparel": [
                "Men Clothing", "Women Clothing", "Kids Clothing", "Footwear"
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
                "Fragrances", "Beauty Tools", "Men's Grooming", "whitening", "Loshion"
            ]
        };

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeImageInputs();
            setupEventListeners();
            setupLivePreview();
            setupVideoUpload();
            setupFormValidation();
            
            // Initialize price calculations
            calculatePrices();
            
            if (state.editingMode) {
                autoFillForm();
                loadExistingData();
                // Update UI for edit mode
                document.getElementById('submitText').textContent = 'Update Product';
            } else {
                document.getElementById('submitText').textContent = 'Submit Product';
            }
        });

        // Initialize image inputs
        function initializeImageInputs() {
            const requiredContainer = document.getElementById('requiredImagesContainer');
            if (!requiredContainer) return;

            const template = document.getElementById('imageUploadTemplate').content;
            
            // In edit mode, we might not need all 5 required images if we already have existing ones
            const existingCount = document.querySelectorAll('.existing-image').length;
            const requiredCount = state.editingMode ? Math.max(0, 5 - existingCount) : 5;
            
            for (let i = 0; i < requiredCount; i++) {
                const clone = document.importNode(template, true);
                const container = clone.querySelector('.image-upload-container');
                const input = clone.querySelector('input[type="file"]');
                const count = clone.querySelector('.image-count');
                const previewContainer = clone.querySelector('.image-preview-container');
                const previewImg = clone.querySelector('.image-preview');
                const uploadLabel = clone.querySelector('.image-upload-label');
                const removeBtn = clone.querySelector('.remove-image-btn');
                const loadingOverlay = clone.querySelector('.loading-overlay');

                // Set attributes
                container.dataset.index = i;
                count.textContent = existingCount + i + 1;
                input.name = `productImages[]`;
                input.dataset.index = i;
                input.required = i < requiredCount; // Only required for new uploads in create mode

                // Add event listeners
                input.addEventListener('change', function(e) {
                    handleImageUpload(e, container, previewContainer, previewImg, uploadLabel, loadingOverlay);
                });

                removeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    removeImage(container, previewContainer, input, uploadLabel, loadingOverlay);
                });

                // Simulate upload progress for demo
                input.addEventListener('click', function() {
                    if (!this.value) {
                        loadingOverlay.classList.add('active');
                        setTimeout(() => {
                            loadingOverlay.classList.remove('active');
                        }, 500);
                    }
                });

                requiredContainer.appendChild(clone);
            }
            
            // Update state
            state.totalImages = requiredCount + existingCount;
        }

        // Handle image upload
        function handleImageUpload(e, container, previewContainer, previewImg, uploadLabel, loadingOverlay) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file
            if (!validateImageFile(file)) {
                e.target.value = '';
                return;
            }

            // Show loading
            loadingOverlay.classList.add('active');

            // Simulate upload delay
            setTimeout(() => {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    // Update preview
                    previewImg.src = event.target.result;
                    previewContainer.style.display = 'block';
                    uploadLabel.classList.add('has-image');
                    
                    // Add to preview box
                    updatePreviewImages();
                    
                    // Hide loading
                    loadingOverlay.classList.remove('active');
                    
                    // Validate image count
                    validateImageCount();
                    
                    // Clear error if any
                    clearImageErrors();
                };

                reader.readAsDataURL(file);
            }, 500);
        }

        // Remove image
        function removeImage(container, previewContainer, input, uploadLabel, loadingOverlay) {
            input.value = '';
            previewContainer.style.display = 'none';
            uploadLabel.classList.remove('has-image');
            loadingOverlay.classList.remove('active');
            
            // Update preview box
            updatePreviewImages();
            
            // Validate image count
            validateImageCount();
        }

        // Remove existing image
        function removeExistingImage(btn, imageId) {
            const container = btn.closest('.existing-image');
            const input = container.querySelector('.existing-image-input');
            
            // Hide the container
            container.style.display = 'none';
            
            // Change the input to mark for deletion
            input.name = 'deleted_images[]';
            input.value = imageId;
            
            // Store in state
            state.deletedImages.push(imageId);
            
            // Update image count
            validateImageCount();
            
            // Update preview
            updatePreviewImages();
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

        // Validate image count
        function validateImageCount() {
            const newInputs = document.querySelectorAll('input[name="productImages[]"]');
            const existingInputs = document.querySelectorAll('input[name="existing_images[]"]');
            const deletedInputs = document.querySelectorAll('input[name="deleted_images[]"]');
            
            let newFilledCount = 0;
            newInputs.forEach(input => {
                if (input.files.length > 0) newFilledCount++;
            });
            
            const existingCount = existingInputs.length - deletedInputs.length;
            const totalImages = newFilledCount + existingCount;
            
            const addMoreContainer = document.getElementById('addMoreContainer');
            if (totalImages >= state.maxImages) {
                addMoreContainer.style.display = 'none';
            } else {
                addMoreContainer.style.display = 'block';
            }
            
            // Return true if we have at least 5 images total (new + existing - deleted)
            return totalImages >= 5;
        }

        // Clear image errors
        function clearImageErrors() {
            const imageInputs = document.querySelectorAll('input[name="productImages[]"]');
            const existingImages = document.querySelectorAll('.existing-image');
            let hasImages = false;
            
            imageInputs.forEach(input => {
                if (input.files.length > 0) hasImages = true;
            });
            
            if (existingImages.length > 0) hasImages = true;
            
            if (hasImages) {
                const imageSection = document.querySelector('.form-section');
                if (imageSection) {
                    imageSection.classList.remove('error-section');
                }
            }
        }

        // Add more images
        document.getElementById('addMoreImagesBtn')?.addEventListener('click', function() {
            const newInputs = document.querySelectorAll('input[name="productImages[]"]');
            const existingInputs = document.querySelectorAll('input[name="existing_images[]"]');
            const deletedInputs = document.querySelectorAll('input[name="deleted_images[]"]');
            
            const existingCount = existingInputs.length - deletedInputs.length;
            const newFilledCount = Array.from(newInputs).filter(input => input.files.length > 0).length;
            const totalImages = existingCount + newFilledCount;
            
            if (totalImages >= state.maxImages) return;
            
            const container = document.getElementById('additionalImagesContainer');
            if (!container) return;

            const template = document.getElementById('imageUploadTemplate').content;
            const clone = document.importNode(template, true);
            const imageContainer = clone.querySelector('.image-upload-container');
            const input = clone.querySelector('input[type="file"]');
            const count = clone.querySelector('.image-count');
            const previewContainer = clone.querySelector('.image-preview-container');
            const previewImg = clone.querySelector('.image-preview');
            const uploadLabel = clone.querySelector('.image-upload-label');
            const removeBtn = clone.querySelector('.remove-image-btn');
            const loadingOverlay = clone.querySelector('.loading-overlay');

            // Set attributes
            imageContainer.dataset.index = state.totalImages;
            count.textContent = state.totalImages + 1;
            input.name = `productImages[]`;
            input.dataset.index = state.totalImages;

            // Add event listeners
            input.addEventListener('change', function(e) {
                handleImageUpload(e, imageContainer, previewContainer, previewImg, uploadLabel, loadingOverlay);
            });

            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeImage(imageContainer, previewContainer, input, uploadLabel, loadingOverlay);
            });

            container.appendChild(clone);
            state.totalImages++;
            
            // Update button visibility
            validateImageCount();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Category change
            const mainCategory = document.getElementById('mainCategory');
            if (mainCategory) {
                mainCategory.addEventListener('change', updateSubcategories);
            }
            
            // Price calculation listeners
            const priceInputs = ['sellingPrice', 'mrp'];
            priceInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('input', calculatePrices);
                }
            });
            
            // Description word count
            const descriptionTextarea = document.getElementById('description');
            if (descriptionTextarea) {
                descriptionTextarea.addEventListener('input', updateWordCount);
            }
            
            // Add fault button
            const addFaultBtn = document.getElementById('addFaultBtn');
            if (addFaultBtn) {
                addFaultBtn.addEventListener('click', addFault);
            }
            
            // MRP validation on blur
            const mrpInput = document.getElementById('mrp');
            if (mrpInput) {
                mrpInput.addEventListener('blur', validateMRP);
            }
        }

        // Setup form validation
        function setupFormValidation() {
            const form = document.getElementById('productForm');
            if (!form) return;

            // Add input validation on blur
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                // Clear error on input
                input.addEventListener('input', function() {
                    clearFieldError(this);
                });
            });
            
            // Special handling for price fields
            const priceFields = ['sellingPrice', 'mrp'];
            priceFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function() {
                        // Clear all price errors when any price changes
                        priceFields.forEach(f => clearFieldError(f));
                    });
                }
            });
        }

        // Validate individual field
        function validateField(field) {
            const value = field.value.trim();
            const fieldId = field.id || field.name;
            
            // Clear any existing error first
            clearFieldError(fieldId);
            
            if (field.required && !value) {
                showFieldError(fieldId, 'This field is required');
                return false;
            }
            
            // Specific validations
            switch(fieldId) {
                case 'sellingPrice':
                    if (value && parseFloat(value) <= 0) {
                        showFieldError(fieldId, 'Selling price must be greater than 0');
                        return false;
                    }
                    break;
                case 'quantity':
                    if (value && parseInt(value) < 1) {
                        showFieldError(fieldId, 'Quantity must be at least 1');
                        return false;
                    }
                    break;
                case 'mrp':
                    return validateMRP();
            }
            
            return true;
        }

        // Validate MRP
        function validateMRP() {
            const mrp = parseFloat(document.getElementById('mrp')?.value) || 0;
            const sellingPrice = parseFloat(document.getElementById('sellingPrice')?.value) || 0;
            const gstAmount = sellingPrice * 0.17;
            const sellingPriceWithGST = sellingPrice + gstAmount;
            
            clearFieldError('mrp');
            
            // If MRP is 0 or empty, it's optional
            if (mrp <= 0) {
                hideMRPWarning();
                return true;
            }
            
            // MRP must be greater than selling price
            if (mrp <= sellingPrice) {
                showFieldError('mrp', 'MRP must be greater than selling price to show discount');
                showMRPWarning('MRP must be greater than selling price');
                return false;
            }
            
            // MRP must be at least equal to (selling price + GST)
            if (mrp < sellingPriceWithGST) {
                showFieldError('mrp', `MRP must be at least PKR ${formatCurrencyNumber(sellingPriceWithGST)} (Selling Price + GST)`);
                showMRPWarning(`MRP is below (Selling Price + GST)`);
                return false;
            }
            
            // MRP is valid
            hideMRPWarning();
            return true;
        }

        // Show MRP warning
        function showMRPWarning(message) {
            const warningElement = document.getElementById('mrpWarning');
            const warningText = document.getElementById('mrpWarningText');
            if (warningElement && warningText) {
                warningText.textContent = message;
                warningElement.classList.remove('hidden');
            }
        }

        // Hide MRP warning
        function hideMRPWarning() {
            const warningElement = document.getElementById('mrpWarning');
            if (warningElement) {
                warningElement.classList.add('hidden');
            }
        }

        // Update subcategories
        function updateSubcategories() {
            const mainCategory = document.getElementById("mainCategory");
            const subCategory = document.getElementById("subCategory");
            const modelLabel = document.getElementById("modelLabel");

            if (!mainCategory) return;

            // Clear subcategories
            subCategory.innerHTML = '<option value="">Select subcategory</option>';

            // Update model label
            const categoryMap = {
                "Auto Parts & Accessories": "Model",
                "Car Tools & Maintenance": "Model", 
                "Mobile Accessories": "Model",
                "Perfumes & Fragrances": "ML",
                "Fitness & Gym Equipment": "Specifications"
            };

            const label = categoryMap[mainCategory.value] || "Size";
            modelLabel.textContent = label;

            // Populate subcategories
            if (mainCategory.value && subcategories[mainCategory.value]) {
                subCategory.disabled = false;
                subCategory.classList.remove('bg-gray-100');
                subcategories[mainCategory.value].forEach(sub => {
                    const option = document.createElement("option");
                    option.value = sub;
                    option.textContent = sub;
                    option.selected = sub === "{{ isset($product) ? addslashes($product->subcategory) : '' }}";
                    subCategory.appendChild(option);
                });
                
                // If editing and we have a subcategory, ensure it's selected
                if (state.editingMode && "{{ isset($product) ? $product->subcategory : '' }}") {
                    setTimeout(() => {
                        const subcatValue = "{{ isset($product) ? addslashes($product->subcategory) : '' }}";
                        if (subcatValue) {
                            subCategory.value = subcatValue;
                        }
                    }, 100);
                }
            } else {
                subCategory.disabled = true;
                subCategory.classList.add('bg-gray-100');
            }
        }

        // Setup video upload
        function setupVideoUpload() {
            const videoUpload = document.getElementById('videoUpload');
            const videoPreview = document.getElementById('videoPreview');
            const videoElement = videoPreview.querySelector('video');
            const removeVideoBtn = videoPreview.querySelector('.remove-video');
            const videoUploadLabel = document.getElementById('videoUploadLabel');
            const videoLoading = document.getElementById('videoLoading');
            
            if (!videoUpload) return;
            
            // Video upload handler
            videoUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                // Validate video file
                if (!validateVideoFile(file)) {
                    this.value = '';
                    return;
                }
                
                // Store video file
                state.videoFile = file;
                
                // Show loading
                if (videoLoading) {
                    videoLoading.classList.remove('hidden');
                }
                
                // Create object URL
                const videoURL = URL.createObjectURL(file);
                
                // Set video source
                videoElement.src = videoURL;
                videoElement.load();
                
                // Show preview when video is loaded
                videoElement.addEventListener('loadeddata', function() {
                    if (videoLoading) {
                        videoLoading.classList.add('hidden');
                    }
                    videoPreview.classList.add('active');
                    videoUploadLabel.style.display = 'none';
                    
                    // Update preview in right column
                    updateVideoPreview(videoURL);
                    
                    // Mark existing video for deletion
                    if (state.editingMode) {
                        state.deletedVideo = true;
                        // Add hidden input to delete existing video
                        addDeleteVideoInput();
                    }
                });
                
                // Handle video errors
                videoElement.addEventListener('error', function() {
                    if (videoLoading) {
                        videoLoading.classList.add('hidden');
                    }
                    showError('Failed to load video. Please try another file.');
                    removeVideo();
                });
            });
            
            // Remove video handler
            removeVideoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                removeVideo();
            });
            
            // Remove video function
            function removeVideo() {
                videoUpload.value = '';
                videoPreview.classList.remove('active');
                videoUploadLabel.style.display = 'flex';
                videoElement.src = '';
                state.videoFile = null;
                
                // Clear preview in right column
                const previewVideo = document.getElementById('previewVideo');
                if (previewVideo) {
                    previewVideo.classList.add('hidden');
                }
                
                // If editing mode, mark existing video for deletion
                if (state.editingMode) {
                    state.deletedVideo = true;
                    addDeleteVideoInput();
                }
            }
            
            function addDeleteVideoInput() {
                // Remove any existing delete video input
                const existingInput = document.querySelector('input[name="delete_video"]');
                if (existingInput) {
                    existingInput.remove();
                }
                
                // Add new delete video input
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_video';
                deleteInput.value = '1';
                document.getElementById('productForm').appendChild(deleteInput);
            }
        }

        // Validate video file
        function validateVideoFile(file) {
            const maxSize = 50 * 1024 * 1024; // 50MB
            const allowedTypes = [
                'video/mp4',
                'video/mpeg',
                'video/quicktime',
                'video/x-msvideo',
                'video/webm',
                'video/ogg'
            ];
            
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

        // Update video preview in right column
        function updateVideoPreview(videoURL) {
            const previewVideo = document.getElementById('previewVideo');
            const previewVideoElement = previewVideo.querySelector('video');
            
            if (previewVideo && previewVideoElement) {
                previewVideoElement.src = videoURL;
                previewVideoElement.load();
                previewVideo.classList.remove('hidden');
            }
        }

        // Load existing video
        function loadExistingVideo() {
            @if(isset($product) && $product->video)
                const videoPreview = document.getElementById('videoPreview');
                const videoElement = videoPreview.querySelector('video');
                const videoUploadLabel = document.getElementById('videoUploadLabel');
                
                if (videoElement && videoUploadLabel) {
                    const videoUrl = "{{ asset('storage/' . $product->video) }}";
                    videoElement.src = videoUrl;
                    videoElement.load();
                    videoPreview.classList.add('active');
                    videoUploadLabel.style.display = 'none';
                    
                    // Update preview in right column
                    updateVideoPreview(videoUrl);
                }
            @endif
        }

        // Calculate GST and update price breakdown
        function calculatePrices() {
            const sellingPrice = parseFloat(document.getElementById('sellingPrice')?.value) || 0;
            const mrp = parseFloat(document.getElementById('mrp')?.value) || 0;
            const deliveryCharges = 250; // Fixed delivery charges
            
            // Calculate GST (17% on selling price)
            const gstAmount = sellingPrice * 0.17;
            const sellingPriceWithGST = sellingPrice + gstAmount;
            const totalPrice = sellingPriceWithGST + deliveryCharges;
            
            // Update GST calculation display
            const gstSellingPriceElement = document.getElementById('gstSellingPrice');
            const gstAmountElement = document.getElementById('gstAmount');
            const gstTotalElement = document.getElementById('gstTotal');
            
            if (gstSellingPriceElement) {
                gstSellingPriceElement.textContent = formatCurrency(sellingPrice);
            }
            if (gstAmountElement) {
                gstAmountElement.textContent = formatCurrency(gstAmount);
            }
            if (gstTotalElement) {
                gstTotalElement.textContent = formatCurrency(sellingPriceWithGST);
            }
            
            // Update MRP and discount display
            const mrpDisplay = document.getElementById('mrpDisplay');
            const sellingPriceDisplay = document.getElementById('sellingPriceDisplay');
            const discountCalculation = document.getElementById('discountCalculation');
            const mrpValidation = document.getElementById('mrpValidation');
            const discountPercentage = document.getElementById('discountPercentage');
            
            if (mrpDisplay) {
                mrpDisplay.textContent = formatCurrency(mrp);
            }
            if (sellingPriceDisplay) {
                sellingPriceDisplay.textContent = formatCurrency(sellingPrice);
            }
            
            // Validate MRP and show discount
            if (mrp > 0 && sellingPrice > 0) {
                // Check if MRP is valid for discount
                if (mrp > sellingPrice && mrp >= sellingPriceWithGST) {
                    // Calculate discount percentage
                    const discount = ((mrp - sellingPriceWithGST) / mrp) * 100;
                    
                    // Show discount calculation
                    discountCalculation.classList.remove('hidden');
                    discountPercentage.textContent = `${discount.toFixed(1)}% OFF`;
                    
                    // Show valid validation
                    mrpValidation.classList.remove('hidden');
                    mrpValidation.classList.remove('invalid');
                    mrpValidation.classList.add('valid');
                    mrpValidation.innerHTML = `<i class="fas fa-check-circle mr-1"></i><span>Valid discount: ${discount.toFixed(1)}% off MRP</span>`;
                } else {
                    // MRP is invalid for discount
                    discountCalculation.classList.add('hidden');
                    mrpValidation.classList.remove('hidden');
                    mrpValidation.classList.remove('valid');
                    mrpValidation.classList.add('invalid');
                    
                    if (mrp <= sellingPrice) {
                        mrpValidation.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i><span>MRP must be greater than selling price for discount</span>`;
                    } else {
                        mrpValidation.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i><span>MRP should be at least PKR ${formatCurrencyNumber(sellingPriceWithGST)}</span>`;
                    }
                }
            } else {
                // No MRP or no selling price
                discountCalculation.classList.add('hidden');
                mrpValidation.classList.add('hidden');
            }
            
            // Update live preview
            updatePricePreview();
        }

        // Update price preview in right column
        function updatePricePreview() {
            const sellingPrice = parseFloat(document.getElementById('sellingPrice')?.value) || 0;
            const mrp = parseFloat(document.getElementById('mrp')?.value) || 0;
            const deliveryCharges = 250;
            
            // Calculate GST (17% on selling price)
            const gstAmount = sellingPrice * 0.17;
            const sellingPriceWithGST = sellingPrice + gstAmount;
            // const totalPrice = sellingPriceWithGST + deliveryCharges;
            const totalPrice = sellingPriceWithGST;
            
            // Update price breakdown elements
            document.getElementById('previewSellingPrice').textContent = formatCurrency(sellingPrice);
            document.getElementById('previewGST').textContent = formatCurrency(gstAmount);
            document.getElementById('previewDelivery').textContent = formatCurrency(deliveryCharges);
            document.getElementById('previewTotalPrice').textContent = formatCurrency(totalPrice);
            
            // Update MRP and discount display
            const mrpContainer = document.getElementById('previewMRPContainer');
            const discountContainer = document.getElementById('previewDiscountContainer');
            const previewMRP = document.getElementById('previewMRP');
            const previewDiscount = document.getElementById('previewDiscount');
            
            if (mrp > 0 && sellingPrice > 0 && mrp > sellingPrice) {
                // Calculate discount
                const discount = ((mrp - sellingPriceWithGST) / mrp) * 100;
                
                // Show MRP and discount
                mrpContainer.classList.remove('hidden');
                discountContainer.classList.remove('hidden');
                previewMRP.textContent = formatCurrency(mrp);
                previewDiscount.textContent = `${discount.toFixed(1)}% OFF`;
            } else {
                // Hide MRP and discount
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
            
            // Update preview
            updateLivePreview();
        }

        // Add fault
        function addFault() {
            const template = document.getElementById('faultTemplate').content;
            const container = document.getElementById('faultsContainer');
            
            if (!template || !container) return;

            const clone = document.importNode(template, true);
            const imageContainer = clone.querySelector('.image-upload-container');
            const fileInput = clone.querySelector('input[type="file"]');
            const previewContainer = clone.querySelector('.image-preview-container');
            const previewImg = clone.querySelector('.image-preview');
            const uploadLabel = clone.querySelector('.image-upload-label');
            const descriptionTextarea = clone.querySelector('textarea');
            const charCount = clone.querySelector('.char-count');
            const removeFaultBtn = clone.querySelector('.remove-fault');

            // Image upload handling
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

            // Character count
            descriptionTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;
                charCount.className = count > 400 ? 'text-red-600 font-bold' : 
                                    count > 300 ? 'text-yellow-600' : 'text-gray-600';
            });

            // Remove fault
            removeFaultBtn.addEventListener('click', function() {
                container.removeChild(this.closest('.fault-item'));
            });

            // Image remove button
            const removeImageBtn = clone.querySelector('.remove-image-btn');
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    fileInput.value = '';
                    previewContainer.style.display = 'none';
                    uploadLabel.classList.remove('has-image');
                });
            }

            container.appendChild(clone);
            descriptionTextarea.dispatchEvent(new Event('input'));
        }

        // Setup live preview
        function setupLivePreview() {
            // Listen to all form inputs
            const form = document.getElementById('productForm');
            if (!form) return;

            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', updateLivePreview);
                input.addEventListener('change', updateLivePreview);
            });

            // Initial update
            updateLivePreview();
        }

        // Update live preview
        function updateLivePreview() {
            // Product details
            updatePreviewText('previewName', document.getElementById('productName')?.value);
            updatePreviewText('previewBrand', document.getElementById('brand')?.value);
            updatePreviewText('previewCategory', document.getElementById('mainCategory')?.value);
            updatePreviewText('previewCondition', document.getElementById('condition')?.value);
            updatePreviewText('previewModel', document.getElementById('model')?.value);
            updatePreviewText('previewMadeIn', document.getElementById('madeIn')?.value);
            updatePreviewText('previewLocation', document.getElementById('location')?.value);
            updatePreviewText('previewQuantity', document.getElementById('quantity')?.value);

            // Shipping
            updatePreviewText('previewShippingMethod', document.getElementById('shippingMethod')?.value);
            updatePreviewText('previewShippingTime', document.getElementById('shippingTime')?.value);

            // Description
            const description = document.getElementById('description')?.value || '';
            const previewDescription = document.getElementById('previewDescription');
            if (previewDescription) {
                if (description.trim()) {
                    const wordCount = description.trim().split(/\s+/).filter(w => w.length > 0).length;
                    if (wordCount > 100) {
                        previewDescription.innerHTML = `<div class="text-green-600 font-medium mb-1">✓ ${wordCount} words (Good)</div>` +
                            `<div class="text-gray-700 text-sm">${description.substring(0, 200)}${description.length > 200 ? '...' : ''}</div>`;
                    } else if (wordCount > 50) {
                        previewDescription.innerHTML = `<div class="text-yellow-600 font-medium mb-1">⚠ ${wordCount} words (Needs more)</div>` +
                            `<div class="text-gray-700 text-sm">${description.substring(0, 150)}${description.length > 150 ? '...' : ''}</div>`;
                    } else {
                        previewDescription.innerHTML = `<div class="text-red-600 font-medium mb-1">✗ ${wordCount} words (Too short)</div>` +
                            `<div class="text-gray-700 text-sm">${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</div>`;
                    }
                } else {
                    previewDescription.innerHTML = `
                        <div class="text-gray-500 italic text-center py-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            No description provided
                        </div>
                    `;
                }
            }

            // Update prices
            calculatePrices();
            
            // Update image preview
            updatePreviewImages();
            
            // Update preview status
            const existingImages = document.querySelectorAll('.existing-image:not([style*="display: none"])').length;
            const newImages = document.querySelectorAll('input[name="productImages[]"]:valid').length;
            const totalImages = existingImages + newImages;
            document.getElementById('previewStatus').textContent = 
                `${totalImages}/5 images uploaded | Live preview active`;
        }

        // Update preview text
        function updatePreviewText(elementId, value) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = value?.trim() || '-';
                element.className = value?.trim() ? 
                    'text-gray-800 font-medium text-right max-w-[60%]' :
                    'text-gray-400 italic text-right max-w-[60%]';
            }
        }

        // Format currency
        function formatCurrency(amount) {
            return 'PKR ' + amount.toLocaleString('en-PK', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Format currency number only
        function formatCurrencyNumber(amount) {
            return amount.toLocaleString('en-PK', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Update preview images
        function updatePreviewImages() {
            const previewContainer = document.getElementById('previewImages');
            if (!previewContainer) return;

            // Get all uploaded images
            const newImageInputs = document.querySelectorAll('input[name="productImages[]"]');
            const uploadedImages = Array.from(newImageInputs)
                .filter(input => input.files.length > 0)
                .map(input => URL.createObjectURL(input.files[0]));

            // Get existing images
            const existingImages = document.querySelectorAll('.existing-image:not([style*="display: none"]) img');
            const existingImageUrls = Array.from(existingImages).map(img => img.src);

            // Combine all images
            const allImages = [...existingImageUrls, ...uploadedImages];

            // Clear existing previews
            previewContainer.innerHTML = '';

            // Add image previews
            allImages.forEach((imageUrl, index) => {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'preview-image';
                previewDiv.innerHTML = `
                    <img src="${imageUrl}" alt="Product Image ${index + 1}" />
                    <div class="preview-image-count">${index + 1}</div>
                `;
                previewContainer.appendChild(previewDiv);
            });

            // Add placeholders for remaining slots
            const remainingSlots = Math.max(0, 4 - allImages.length);
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
        }

        // Auto-fill form for editing
        function autoFillForm() {
            @if(isset($product) && $product)
                // Trigger category change to populate subcategories
                const mainCategory = document.getElementById('mainCategory');
                if (mainCategory) {
                    // Trigger change event after a short delay to ensure DOM is ready
                    setTimeout(() => {
                        mainCategory.dispatchEvent(new Event('change'));
                    }, 100);
                }
                
                // Update word count
                const descriptionTextarea = document.getElementById('description');
                if (descriptionTextarea) {
                    setTimeout(() => {
                        updateWordCount.call(descriptionTextarea);
                    }, 200);
                }
            @endif
        }

        // Load existing data
        function loadExistingData() {
            // Load existing video
            loadExistingVideo();
            
            // Update preview images with existing ones
            updatePreviewImages();
            
            // Calculate prices
            calculatePrices();
        }

        // Handle form submission
        async function handleFormSubmit(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearErrors();
            
            // Validate form
            if (!validateForm()) {
                scrollToFirstError();
                return false;
            }
            
            // Show loading state
            setSubmitButtonLoading(true);
            
            try {
                // Submit the form
                const form = e.target;
                const formData = new FormData(form);
                
                // Add editing mode flag
                if (state.editingMode) {
                    formData.append('is_update', 'true');
                }
                
                // Submit via AJAX
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // Check if response is JSON
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    const result = await response.json();
                    
                    if (result.success) {
                        // Success handling
                        showSuccess(result.message || (state.editingMode ? 'Product updated successfully!' : 'Product submitted successfully!'));
                        setTimeout(() => {
                            window.location.href = result.redirect || '/vendor/products';
                        }, 1500);
                    } else {
                        // Error handling
                        setSubmitButtonLoading(false);
                        
                        if (result.errors) {
                            // Display field errors
                            Object.entries(result.errors).forEach(([field, messages]) => {
                                showFieldError(field, messages[0]);
                            });
                            scrollToFirstError();
                        } else {
                            showError(result.message || (state.editingMode ? 'Failed to update product.' : 'Failed to submit product.'));
                        }
                    }
                } else {
                    // Not a JSON response, might be HTML or redirect
                    setSubmitButtonLoading(false);
                    showError('Server returned unexpected response. Please try again.');
                    console.error('Non-JSON response received');
                }
            } catch (error) {
                setSubmitButtonLoading(false);
                showError('Network error. Please check your connection and try again.');
                console.error('Form submission error:', error);
            }
            
            return false;
        }

        // Validate form
        function validateForm() {
            let isValid = true;
            
            // Required fields
            const requiredFields = [
                {id: 'productName', name: 'product_name'},
                {id: 'mainCategory', name: 'category'},
                {id: 'subCategory', name: 'subcategory'},
                {id: 'brand', name: 'brand'},
                {id: 'model', name: 'model'},
                {id: 'madeIn', name: 'made_in'},
                {id: 'condition', name: 'condition'},
                {id: 'sellingPrice', name: 'selling_price'},
                {id: 'quantity', name: 'quantity'},
                {id: 'shippingMethod', name: 'shipping_method'},
                {id: 'shippingTime', name: 'shipping_time'},
                {id: 'location', name: 'location'}
            ];
            
            requiredFields.forEach(field => {
                const element = document.getElementById(field.id);
                if (element && !element.value.trim()) {
                    showFieldError(field.id, `This field is required`);
                    isValid = false;
                }
            });
            
            // Image validation - check total image count (existing + new - deleted)
            const newInputs = document.querySelectorAll('input[name="productImages[]"]');
            const existingInputs = document.querySelectorAll('input[name="existing_images[]"]');
            const deletedInputs = document.querySelectorAll('input[name="deleted_images[]"]');
            
            let newFilledCount = 0;
            newInputs.forEach(input => {
                if (input.files.length > 0) newFilledCount++;
            });
            
            const existingCount = existingInputs.length - deletedInputs.length;
            const totalImages = newFilledCount + existingCount;
            
            if (totalImages < 5) {
                const imageSection = document.querySelector('.form-section');
                if (imageSection) {
                    imageSection.classList.add('error-section');
                }
                showError(`Please upload at least 5 product images total (existing + new). Currently have ${totalImages}.`);
                isValid = false;
            }
            
            // Description word count
            const description = document.getElementById('description')?.value || '';
            const wordCount = description.trim().split(/\s+/).filter(w => w.length > 0).length;
            if (wordCount < 100) {
                showFieldError('description', `Description must have at least 100 words (currently ${wordCount})`);
                isValid = false;
            }
            
            // Price validation
            const sellingPrice = parseFloat(document.getElementById('sellingPrice')?.value) || 0;
            const mrp = parseFloat(document.getElementById('mrp')?.value) || 0;
            
            // Clear price errors first
            clearFieldError('sellingPrice');
            clearFieldError('mrp');
            
            // Validate selling price
            if (sellingPrice <= 0) {
                showFieldError('sellingPrice', 'Selling price must be greater than 0');
                isValid = false;
            }
            
            // Validate MRP if provided
            if (mrp > 0) {
                const gstAmount = sellingPrice * 0.17;
                const sellingPriceWithGST = sellingPrice + gstAmount;
                
                if (mrp <= sellingPrice) {
                    showFieldError('mrp', 'MRP must be greater than selling price');
                    isValid = false;
                }
                
                if (mrp < sellingPriceWithGST) {
                    showFieldError('mrp', `MRP must be at least PKR ${formatCurrencyNumber(sellingPriceWithGST)} (Selling Price + GST)`);
                    isValid = false;
                }
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
            // Handle field name mapping
            let fieldId = field;
            
            // Map field names to element IDs
            const fieldMap = {
                'selling_price': 'sellingPrice',
                'delivery_charges': 'deliveryCharges',
                'product_name': 'productName',
                'made_in': 'madeIn',
                'shipping_method': 'shippingMethod',
                'shipping_time': 'shippingTime'
            };
            
            if (fieldMap[field]) {
                fieldId = fieldMap[field];
            }
            
            const element = document.getElementById(fieldId);
            if (element) {
                element.classList.add('has-error');
                element.closest('.form-section')?.classList.add('error-section');
                
                const errorElement = document.getElementById(fieldId + 'Error');
                if (errorElement) {
                    errorElement.innerHTML = `<i class="fas fa-exclamation-circle error-icon"></i> ${message}`;
                    errorElement.classList.remove('hidden');
                }
            }
        }

        // Clear field error
        function clearFieldError(field) {
            const fieldId = field.id || field;
            const element = document.getElementById(fieldId);
            if (element) {
                element.classList.remove('has-error');
                element.closest('.form-section')?.classList.remove('error-section');
                
                const errorElement = document.getElementById(fieldId + 'Error');
                if (errorElement) {
                    errorElement.classList.add('hidden');
                    errorElement.innerHTML = '';
                }
            }
        }

        // Clear all errors
        function clearErrors() {
            // Remove error classes
            document.querySelectorAll('.has-error').forEach(el => {
                el.classList.remove('has-error');
            });
            
            document.querySelectorAll('.error-section').forEach(el => {
                el.classList.remove('error-section');
            });
            
            // Hide error messages
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.add('hidden');
                el.innerHTML = '';
            });
            
            // Hide notifications
            hideError();
            hideSuccess();
        }

        // Show error notification
        function showError(message, title = 'Validation Error') {
            const notification = document.getElementById('errorNotification');
            const titleElement = document.getElementById('errorTitle');
            const contentElement = document.getElementById('errorContent');
            
            if (notification && titleElement && contentElement) {
                titleElement.textContent = title;
                contentElement.querySelector('p').textContent = message;
                notification.classList.remove('hidden');
                
                // Auto-hide after 8 seconds
                setTimeout(() => {
                    hideError();
                }, 8000);
            }
        }

        // Hide error notification
        function hideError() {
            const notification = document.getElementById('errorNotification');
            if (notification) {
                notification.classList.add('hidden');
            }
        }

        // Show success notification
        function showSuccess(message, title = 'Success!') {
            const notification = document.getElementById('successNotification');
            const titleElement = document.getElementById('successTitle');
            const contentElement = document.getElementById('successContent');
            
            if (notification && titleElement && contentElement) {
                titleElement.textContent = title;
                contentElement.querySelector('p').textContent = message;
                notification.classList.remove('hidden');
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    hideSuccess();
                }, 5000);
            }
        }

        // Hide success notification
        function hideSuccess() {
            const notification = document.getElementById('successNotification');
            if (notification) {
                notification.classList.add('hidden');
            }
        }

        // Set submit button loading state
        function setSubmitButtonLoading(isLoading) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');
            
            if (submitBtn && submitText && submitLoader) {
                submitBtn.disabled = isLoading;
                if (isLoading) {
                    submitText.textContent = state.editingMode ? 'Updating...' : 'Processing...';
                    submitLoader.classList.remove('hidden');
                } else {
                    submitText.textContent = state.editingMode ? 'Update Product' : 'Submit Product';
                    submitLoader.classList.add('hidden');
                }
            }
        }

        // Scroll to first error
        function scrollToFirstError() {
            const firstError = document.querySelector('.has-error');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        // Add form submit event listener
        const form = document.getElementById('productForm');
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }

        // Add slide animations
        const style = document.createElement('style');
        style.textContent = `
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
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>