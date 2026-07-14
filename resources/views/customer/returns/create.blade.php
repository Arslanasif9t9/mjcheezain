<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Item | Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        .dropzone { border: 2px dashed #cbd5e0; border-radius: 10px; }
        .dropzone.active { border-color: #3b82f6; background-color: #eff6ff; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 sm:p-6 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Return Item Request</h1>
                    <a href="{{ route('customer.orders') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                    </a>
                </div>
                <p class="text-gray-600">Fill out the form below to request a return for your item</p>
            </div>

            <!-- Order Details -->
            <div class="mb-8 bg-blue-50 rounded-xl p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Order Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Order ID</p>
                        <p class="font-medium">ORD-{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Order Date</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="mb-8 bg-white border border-gray-200 rounded-xl p-4 sm:p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Product Details</h2>
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    @if($cart->product_image)
                        <img src="{{ asset('storage/vendor/products/images/' . $cart->product_image) }}" 
                             class="w-24 h-24 rounded-lg object-cover border border-gray-200 w-full sm:w-24">
                    @endif
                    <div class="flex-1 w-full">
                        <h3 class="font-bold text-lg text-gray-800">{{ $cart->product_name }}</h3>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-sm text-gray-500">Quantity to Return</p>
                                <p class="font-medium">{{ $cart->quantity }} item(s)</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Price per item</p>
                                <p class="font-medium">${{ number_format($cart->price, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Refund</p>
                                <p class="font-bold text-green-600">${{ number_format($cart->price * $cart->quantity, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return Form -->
            <form id="returnForm">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                <input type="hidden" name="product_id" value="{{ $cart->product_id }}">
                <input type="hidden" name="quantity" value="{{ $cart->quantity }}">

                <!-- Reason Selection -->
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Select Return Reason</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="reason" value="damaged" class="mr-3" required>
                            <div>
                                <span class="font-medium">Damaged Item</span>
                                <p class="text-sm text-gray-500 mt-1">Item arrived damaged or broken</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="reason" value="wrong_item" class="mr-3">
                            <div>
                                <span class="font-medium">Wrong Item</span>
                                <p class="text-sm text-gray-500 mt-1">Received different item than ordered</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="reason" value="defective" class="mr-3">
                            <div>
                                <span class="font-medium">Defective Product</span>
                                <p class="text-sm text-gray-500 mt-1">Item doesn't work properly</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="reason" value="size_issue" class="mr-3">
                            <div>
                                <span class="font-medium">Size Issue</span>
                                <p class="text-sm text-gray-500 mt-1">Wrong size received</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="reason" value="color_issue" class="mr-3">
                            <div>
                                <span class="font-medium">Color Issue</span>
                                <p class="text-sm text-gray-500 mt-1">Wrong color received</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="reason" value="not_as_described" class="mr-3">
                            <div>
                                <span class="font-medium">Not as Described</span>
                                <p class="text-sm text-gray-500 mt-1">Item differs from description</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="reason" value="other" class="mr-3">
                            <div>
                                <span class="font-medium">Other Reason</span>
                                <p class="text-sm text-gray-500 mt-1">Other reason not listed</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Details</label>
                    <textarea name="details" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Please provide more details about why you're returning this item..."></textarea>
                </div>

                <!-- Image Upload -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images (Optional)</label>
                    <p class="text-sm text-gray-500 mb-4">Upload photos to help us understand the issue better</p>
                    
                    <div id="dropzone" class="dropzone p-8 text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Drag & drop images here or click to browse</p>
                        <p class="text-sm text-gray-500">Maximum 5 images, 2MB each</p>
                        <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
                    </div>
                    
                    <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-3"></div>
                </div>

                <!-- Return Policy -->
                <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-6">
                    <h3 class="font-bold text-yellow-800 mb-3"><i class="fas fa-exclamation-circle mr-2"></i> Return Policy</h3>
                    <ul class="text-sm text-yellow-700 space-y-2">
                        <li>• Returns must be initiated within 30 days of delivery</li>
                        <li>• Items must be in original condition with all tags</li>
                        <li>• Original shipping costs are non-refundable</li>
                        <li>• Refunds will be processed within 7-10 business days after we receive the item</li>
                        <li>• You may be responsible for return shipping costs</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-500 text-center sm:text-left">
                        <i class="fas fa-info-circle mr-1"></i>
                        By submitting, you agree to our return policy
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row gap-3 w-full sm:w-auto">
                        <button type="button" onclick="window.history.back()" 
                                class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 text-center">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                                class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Return Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropzone = document.getElementById('dropzone');
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const form = document.getElementById('returnForm');
            const submitBtn = document.getElementById('submitBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            let uploadedImages = [];
            
            // Dropzone functionality
            dropzone.addEventListener('click', () => imageInput.click());
            
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('active');
            });
            
            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('active');
            });
            
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('active');
                handleFiles(e.dataTransfer.files);
            });
            
            imageInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });
            
            function handleFiles(files) {
                for (let file of files) {
                    if (file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const imageData = e.target.result;
                            uploadedImages.push({
                                file: file,
                                data: imageData
                            });
                            updatePreview();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        alert('Please upload only images (max 2MB each)');
                    }
                }
            }
            
            function updatePreview() {
                imagePreview.innerHTML = '';
                uploadedImages.forEach((img, index) => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${img.data}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                        <button type="button" onclick="removeImage(${index})" 
                                class="absolute top-2 right-2 bg-red-500 text-white w-6 h-6 rounded-full opacity-0 group-hover:opacity-100 transition">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    `;
                    imagePreview.appendChild(div);
                });
            }
            
            window.removeImage = function(index) {
                uploadedImages.splice(index, 1);
                updatePreview();
            };
            
            // Form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Validate reason
                const reasonSelected = document.querySelector('input[name="reason"]:checked');
                if (!reasonSelected) {
                    alert('Please select a return reason');
                    return;
                }
                
                // Disable button and show loading
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
                submitBtn.disabled = true;
                
                // Create FormData
                const formData = new FormData(form);
                
                // Append images
                uploadedImages.forEach((img, index) => {
                    formData.append(`images[${index}]`, img.file);
                });
                
                try {
                    const response = await fetch("{{ route('customer.returns.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        alert('Return request submitted successfully!');
                        window.location.href = result.redirect;
                    } else {
                        throw new Error(result.message || 'Submission failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error submitting return request: ' + error.message);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
</body>
</html>