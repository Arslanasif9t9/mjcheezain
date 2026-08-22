<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <script src="{{ asset('js/fault-annotator.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Replacement | MJ Cheezain</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-customer.theme />
    <style>
        .dropzone { border: 2px dashed #FFC1D3; border-radius: 14px; background: #fff; }
        .dropzone.active { border-color: #E85D85; background-color: #FFF1F5; }
        .readonly-box { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 12px 16px; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 sm:p-6 max-w-4xl">
        <div class="app-card p-4 sm:p-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Request Replacement</h1>
                    <a href="{{ route('customer.orders') }}" class="text-brand hover:opacity-70 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                    </a>
                </div>
                <p class="text-gray-600">Fill out the form below to request a replacement for your item</p>
            </div>

            <!-- Order + Product Details -->
            <div class="mb-8 brand-gradient-soft rounded-xl p-6 border border-pink-100">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Order Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Order Number</p>
                        <p class="font-medium">ORD-{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Order Date</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-8 bg-white border border-pink-100 rounded-xl p-4 sm:p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Product Details</h2>
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    @if($cart->product_image)
                        <img src="{{ asset('storage/vendor/products/images/' . $cart->product_image) }}"
                             class="w-24 h-24 rounded-lg object-cover border border-gray-200 w-full sm:w-24">
                    @endif
                    <div class="flex-1 w-full">
                        <h3 class="font-bold text-lg text-gray-800">{{ $cart->product_name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Original Product Price</p>
                        <p class="font-bold text-gray-800">Rs. {{ number_format($cart->price, 2) }}</p>
                    </div>
                </div>
            </div>

            <form id="replacementForm">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                <input type="hidden" name="product_id" value="{{ $cart->product_id }}">
                <input type="hidden" id="originalPrice" value="{{ $cart->price }}">

                <!-- Replacement Reason -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Replacement Reason</label>
                    <select name="reason" id="reasonSelect" required
                            class="w-full px-4 py-3 border border-pink-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300">
                        <option value="" disabled selected>Select a reason...</option>
                        <option value="wrong_product">Wrong Product Received</option>
                        <option value="damaged_product">Damaged Product</option>
                        <option value="defective_product">Defective Product</option>
                        <option value="different_from_description">Product Different From Description</option>
                        <option value="missing_part">Missing Part/Accessory</option>
                        <option value="size_color_issue">Size/Color Issue</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Issue Description -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Issue Description</label>
                    <textarea name="details" rows="4"
                        class="w-full px-4 py-3 border border-pink-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300"
                        placeholder="Please describe the issue with your product..."></textarea>
                </div>

                <!-- Photo Upload -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Photos</label>
                    <p class="text-sm text-gray-500 mb-4">Upload photos to help us understand the issue better. Use the pencil icon on a photo to mark/circle the fault.</p>

                    <div id="dropzone" class="dropzone p-8 text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Drag & drop images here or click to browse</p>
                        <p class="text-sm text-gray-500">Maximum 5 images, 5MB each</p>
                        <input type="file" id="imageInput" multiple accept="image/*" class="hidden">
                    </div>

                    <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-3"></div>
                </div>

                <!-- Video Upload -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Video (Optional)</label>
                    <p class="text-sm text-gray-500 mb-3">
                        <i class="fas fa-microphone mr-1"></i>
                        Please explain by voice in the video why you want this product replaced — it helps us
                        process your request faster.
                    </p>
                    <input type="file" name="video" id="videoInput" accept="video/*"
                           class="w-full px-4 py-3 border border-pink-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300">
                    <p class="text-xs text-gray-400 mt-1">Max 50MB.</p>
                </div>

                <!-- Replacement Product Picker -->
                <div class="mb-8 bg-white border border-pink-100 rounded-xl p-4 sm:p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-2">Select Replacement Product</h2>
                    <p class="text-sm text-gray-500 mb-4">
                        Only products from the same store, priced the same or higher than your original product,
                        are shown. Replacement is not a refund — a cheaper product cannot be selected.
                    </p>
                    <select name="replacement_product_id" id="replacementProductSelect" required
                            class="w-full px-4 py-3 border border-pink-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300">
                        <option value="" disabled selected>Select a replacement product...</option>
                        @foreach($replacementCandidates as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}" data-free-delivery="{{ $p->free_delivery ? 1 : 0 }}">
                                {{ $p->name }} — Rs. {{ number_format($p->selling_price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @if($replacementCandidates->isEmpty())
                        <p class="text-sm text-red-500 mt-2">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            No same-store products at this price or higher are available right now.
                        </p>
                    @endif

                    <!-- Auto-calculated summary -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                        <div class="readonly-box">
                            <p class="text-xs text-gray-500">Original Product Price</p>
                            <p class="font-bold text-gray-800">Rs. {{ number_format($cart->price, 2) }}</p>
                        </div>
                        <div class="readonly-box">
                            <p class="text-xs text-gray-500">Replacement Product Price</p>
                            <p class="font-bold text-gray-800" id="replacementPriceDisplay">Rs. 0.00</p>
                        </div>
                        <div class="readonly-box">
                            <p class="text-xs text-gray-500">Additional Amount Payable</p>
                            <p class="font-bold text-gray-800" id="additionalAmountDisplay">Rs. 0.00</p>
                        </div>
                        <div class="readonly-box">
                            <p class="text-xs text-gray-500">Replacement Delivery Charges</p>
                            <p class="font-bold text-gray-800" id="deliveryChargeDisplay">Rs. 0.00</p>
                        </div>
                        <div class="readonly-box sm:col-span-2 border-pink-200 bg-pink-50">
                            <p class="text-xs text-gray-500">Total Amount Payable</p>
                            <p class="font-bold text-lg text-[#E85D85]" id="totalAmountDisplay">Rs. 0.00</p>
                            <p class="text-xs text-gray-400 mt-1" id="deliveryNote"></p>
                        </div>
                    </div>
                </div>

                <!-- Replacement Policy -->
                <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-6">
                    <h3 class="font-bold text-yellow-800 mb-3"><i class="fas fa-exclamation-circle mr-2"></i> Replacement Policy</h3>
                    <ul class="text-sm text-yellow-700 space-y-2">
                        <li>• Replacement is not a refund — there is no cash-back path in this flow</li>
                        <li>• The replacement product must be from the same store as the original</li>
                        <li>• The replacement product's price must be the same as or higher than the original</li>
                        <li>• If the replacement costs more, you pay the price difference shown above</li>
                        <li>• If the original issue was our fault (wrong/damaged/defective/different-from-description/missing part), we cover the replacement courier cost</li>
                    </ul>
                </div>

                <!-- Confirmation -->
                <div class="mb-8">
                    <label class="flex items-start gap-3 p-4 border border-pink-100 rounded-xl bg-white cursor-pointer">
                        <input type="checkbox" name="customer_confirmed" id="confirmCheckbox" required class="mt-1">
                        <span class="text-sm text-gray-700">I agree to the Replacement Policy</span>
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-500 text-center sm:text-left">
                        <i class="fas fa-info-circle mr-1"></i>
                        By submitting, you agree to our replacement policy
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row gap-3 w-full sm:w-auto">
                        <button type="button" onclick="window.history.back()"
                                class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 text-center">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                                class="w-full sm:w-auto px-6 py-3 brand-gradient brand-shadow text-white rounded-full font-semibold hover:opacity-90 flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Replacement Request
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
            const form = document.getElementById('replacementForm');
            const submitBtn = document.getElementById('submitBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const originalPrice = parseFloat(document.getElementById('originalPrice').value) || 0;
            const reasonSelect = document.getElementById('reasonSelect');
            const productSelect = document.getElementById('replacementProductSelect');

            // Vendor-fault reasons -> we cover the replacement courier cost (mirrors
            // ReplacementController::VENDOR_FAULT_REASONS server-side — this is UX preview only).
            const VENDOR_FAULT_REASONS = ['wrong_product', 'damaged_product', 'defective_product', 'different_from_description', 'missing_part'];
            const FLAT_DELIVERY_FEE = 300;

            // uploadedImages holds {file, previewEl, fileInput} so each photo can carry
            // its own FaultAnnotator-editable file input.
            let uploadedImages = [];

            // ---- Photo dropzone ----
            dropzone.addEventListener('click', () => imageInput.click());
            dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('active'); });
            dropzone.addEventListener('dragleave', () => dropzone.classList.remove('active'));
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('active');
                handleFiles(e.dataTransfer.files);
            });
            imageInput.addEventListener('change', (e) => { handleFiles(e.target.files); imageInput.value = ''; });

            function handleFiles(files) {
                for (let file of files) {
                    if (uploadedImages.length >= 5) { alert('Maximum 5 images allowed'); break; }
                    if (file.type.startsWith('image/') && file.size <= 5 * 1024 * 1024) {
                        addImageEntry(file);
                    } else {
                        alert('Please upload only images (max 5MB each)');
                    }
                }
            }

            function addImageEntry(file) {
                const entry = { file: file };
                uploadedImages.push(entry);
                renderPreview();
            }

            function renderPreview() {
                imagePreview.innerHTML = '';
                uploadedImages.forEach((entry, index) => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.style.position = 'relative';

                    const img = document.createElement('img');
                    img.className = 'w-full h-32 object-cover rounded-lg border border-gray-200';
                    const reader = new FileReader();
                    reader.onload = (e) => { img.src = e.target.result; };
                    reader.readAsDataURL(entry.file);
                    div.appendChild(img);

                    // Hidden single-file input so FaultAnnotator can annotate this exact photo.
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'file';
                    hiddenInput.accept = 'image/*';
                    hiddenInput.className = 'hidden';
                    const dt = new DataTransfer();
                    dt.items.add(entry.file);
                    hiddenInput.files = dt.files;
                    hiddenInput.addEventListener('change', function() {
                        const f = hiddenInput.files && hiddenInput.files[0];
                        if (f) {
                            entry.file = f;
                            const r = new FileReader();
                            r.onload = (e) => { img.src = e.target.result; };
                            r.readAsDataURL(f);
                        }
                    });
                    div.appendChild(hiddenInput);

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'absolute top-2 right-2 bg-red-500 text-white w-6 h-6 rounded-full opacity-0 group-hover:opacity-100 transition';
                    removeBtn.style.zIndex = 25;
                    removeBtn.innerHTML = '<i class="fas fa-times text-xs"></i>';
                    removeBtn.addEventListener('click', function() {
                        uploadedImages.splice(index, 1);
                        renderPreview();
                    });
                    div.appendChild(removeBtn);

                    imagePreview.appendChild(div);

                    if (window.FaultAnnotator) {
                        window.FaultAnnotator.attachBadge(div, hiddenInput);
                    }
                });
            }

            // ---- Live price preview ----
            function updatePricePreview() {
                const selected = productSelect.options[productSelect.selectedIndex];
                const hasSelection = selected && selected.value;
                const replacementPrice = hasSelection ? parseFloat(selected.getAttribute('data-price')) || 0 : 0;
                const freeDelivery = hasSelection ? selected.getAttribute('data-free-delivery') === '1' : false;

                const additional = Math.max(0, replacementPrice - originalPrice);
                const deliveryCharge = freeDelivery ? 0 : FLAT_DELIVERY_FEE;

                const reason = reasonSelect.value;
                const vendorPaysCourier = VENDOR_FAULT_REASONS.includes(reason);
                const total = additional + (vendorPaysCourier ? 0 : deliveryCharge);

                document.getElementById('replacementPriceDisplay').textContent = 'Rs. ' + replacementPrice.toFixed(2);
                document.getElementById('additionalAmountDisplay').textContent = 'Rs. ' + additional.toFixed(2);
                document.getElementById('deliveryChargeDisplay').textContent = 'Rs. ' + deliveryCharge.toFixed(2);
                document.getElementById('totalAmountDisplay').textContent = 'Rs. ' + total.toFixed(2);

                const note = document.getElementById('deliveryNote');
                if (!reason) {
                    note.textContent = 'Select a reason to see who covers the delivery charge.';
                } else if (vendorPaysCourier) {
                    note.textContent = 'Based on your selected reason, we cover the replacement delivery charge — it is not included above.';
                } else {
                    note.textContent = 'Based on your selected reason, the delivery charge is included above.';
                }
            }

            reasonSelect.addEventListener('change', updatePricePreview);
            productSelect.addEventListener('change', updatePricePreview);
            updatePricePreview();

            // ---- Form submission ----
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!reasonSelect.value) { alert('Please select a replacement reason'); return; }
                if (!productSelect.value) { alert('Please select a replacement product'); return; }
                if (!document.getElementById('confirmCheckbox').checked) {
                    alert('Please agree to the Replacement Policy to continue');
                    return;
                }

                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
                submitBtn.disabled = true;

                const formData = new FormData(form);
                formData.set('customer_confirmed', '1');
                uploadedImages.forEach((entry, index) => {
                    formData.append(`images[${index}]`, entry.file);
                });

                try {
                    const response = await fetch("{{ route('customer.replacements.store') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Replacement request submitted successfully! Reference: ' + (result.reference_number || ''));
                        window.location.href = result.redirect;
                    } else {
                        throw new Error(result.message || 'Submission failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error submitting replacement request: ' + error.message);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
</body>
</html>
