<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/page-loader.js') }}?v={{ @filemtime(public_path('js/page-loader.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Return Details | Vendor Dashboard</title>
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ @filemtime(public_path('css/tailwind.css')) ?: 1 }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .info-card {
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
            color: white;
            border-radius: 1.25rem;
            box-shadow: 0 6px 18px rgba(255, 125, 160, 0.30);
        }
        
        .timeline-container {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline-container:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 2px;
            height: 100%;
            background: #e5e7eb;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -36px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #f59e0b;
            border: 3px solid white;
        }
        
        .timeline-item.completed:before {
            background: #10b981;
        }
        
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
        }
        
        .image-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .image-item:hover {
            transform: scale(1.05);
        }
        
        .image-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }
        
        /* Image Modal */
        .image-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        .image-modal.open {
            display: flex;
        }
        
        .image-modal img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        
        .close-image-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 24px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="min-h-screen" style="background-color: #FFF6F0;">
    <x-vendor.app-header
        title="Return #{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}"
        subtitle="Return request details"
        back="{{ route('vendor.returns.index') }}" />

    <div class="container mx-auto px-4 py-4 md:py-8 pb-28 md:pb-8 page-enter">
        <!-- Header (desktop only — mobile uses the app header above) -->
        <div class="hidden md:flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Return Details</h1>
                <p class="text-gray-600 mt-2">Return ID: RET-{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('vendor.returns.index') }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Back to Returns
                </a>
                <button onclick="window.print()" 
                        class="px-6 py-3 bg-pink-100 text-[#C94A72] rounded-xl font-semibold hover:bg-pink-200 transition flex items-center gap-2">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 md:gap-8">
            <!-- Left Column - Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="card app-card bg-white p-5 md:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-circle text-orange-600"></i>
                        Customer Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-500">Customer Name</label>
                            <p class="font-medium">{{ $return->customer_first_name ?? 'N/A' }} {{ $return->customer_last_name ?? '' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Email</label>
                            <p class="font-medium">{{ $return->customer_email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Phone</label>
                            <p class="font-medium">{{ $return->customer_phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Address</label>
                            <p class="font-medium">{{ $return->customer_address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="card app-card bg-white p-5 md:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-box text-orange-600"></i>
                        Product Information
                    </h2>
                    <div class="flex items-start gap-4">
                        @php
                            $productImage = DB::table('vendor_product_images')
                                ->where('product_id', $return->product_id)
                                ->where('is_primary', 1)
                                ->first();
                        @endphp
                        @if($productImage)
                            <img src="{{ asset('storage/vendor/products/images/' . $productImage->image_path) }}" 
                                 class="w-24 h-24 rounded-lg object-cover border border-gray-200"
                                 alt="{{ $return->product_name }}">
                        @else
                            <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800">{{ $return->product_name }}</h3>
                            <div class="grid grid-cols-2 gap-4 mt-3">
                                <div>
                                    <label class="text-sm text-gray-500">Order ID</label>
                                    <p class="font-medium">ORD-{{ $return->order_id }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">Order Date</label>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($return->order_date)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">Return Reason</label>
                                    <p class="font-medium capitalize">{{ str_replace('_', ' ', $return->reason) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">Details</label>
                                    <p class="font-medium">{{ $return->details ?? 'No additional details' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return Images -->
                @if($images && count($images) > 0)
                <div class="card app-card bg-white p-5 md:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-images text-orange-600"></i>
                        Return Images
                    </h2>
                    <div class="image-gallery">
                        @foreach($images as $image)
                        <div class="image-item" onclick="openImageModal('{{ asset('storage/' . $image->image_path) }}')">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 alt="Return Image {{ $loop->iteration }}">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Tracking Timeline -->
                <div class="card app-card bg-white p-5 md:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-history text-orange-600"></i>
                        Return Timeline
                    </h2>
                    <div class="timeline-container">
                        @forelse($tracking as $track)
                        <div class="timeline-item {{ $track->status === 'completed' ? 'completed' : '' }}">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $track->step)) }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $track->description }}</p>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($track->created_at)->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-history text-3xl mb-3"></i>
                            <p>No tracking information available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Status & Actions -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="info-card p-6">
                    <h2 class="text-xl font-bold mb-4">Current Status</h2>
                    <div class="text-center">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-500',
                                'approved' => 'bg-green-500',
                                'processing' => 'bg-pink-500',
                                'refunded' => 'bg-teal-500',
                                'completed' => 'bg-green-600',
                                'rejected' => 'bg-red-500'
                            ];
                            $statusColor = $statusColors[$return->status] ?? 'bg-gray-500';
                        @endphp
                        <span class="status-badge {{ $statusColor }} text-white text-lg">
                            {{ ucfirst($return->status) }}
                        </span>
                        <p class="mt-4 opacity-90">Return ID: RET-{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="opacity-90">Created: {{ \Carbon\Carbon::parse($return->created_at)->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card app-card bg-white p-5 md:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <button onclick="window.location.href='{{ route('vendor.returns.index') }}?update={{ $return->id }}'"
                                class="w-full py-3 brand-gradient brand-shadow text-white rounded-full font-semibold hover:opacity-90 transition flex items-center justify-center gap-2 border-0">
                            <i class="fas fa-edit"></i> Update Status
                        </button>
                        <button onclick="contactCustomer()"
                                class="w-full py-3 bg-[#E85D85] text-white rounded-full font-semibold hover:bg-[#C94A72] transition flex items-center justify-center gap-2 border-0">
                            <i class="fas fa-comment"></i> Contact Customer
                        </button>
                        <button onclick="generateLabel()"
                                class="w-full py-3 bg-green-600 text-white rounded-full font-semibold hover:bg-green-700 transition flex items-center justify-center gap-2 border-0">
                            <i class="fas fa-tag"></i> Generate Label
                        </button>
                        <button onclick="window.print()"
                                class="md:hidden w-full py-3 brand-gradient-soft text-[#E85D85] rounded-full font-semibold transition flex items-center justify-center gap-2 border border-[#E85D85]/20">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>

                <!-- Return Details -->
                <div class="card app-card bg-white p-5 md:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Return Details</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">Tracking Number</label>
                            <p class="font-medium">{{ $return->tracking_number ?? 'Not available' }}</p>
                        </div>
                        @if($return->refund_amount)
                        <div>
                            <label class="text-sm text-gray-500">Refund Amount</label>
                            <p class="font-medium text-green-600">Rs. {{ number_format($return->refund_amount, 2) }}</p>
                        </div>
                        @endif
                        @if($return->refund_method)
                        <div>
                            <label class="text-sm text-gray-500">Refund Method</label>
                            <p class="font-medium">
                                <span class="refund-method {{ 'refund-' . str_replace('_', '-', $return->refund_method) }}">
                                    {{ ucfirst(str_replace('_', ' ', $return->refund_method)) }}
                                </span>
                            </p>
                        </div>
                        @endif
                        @if($return->pickup_scheduled_date)
                        <div>
                            <label class="text-sm text-gray-500">Pickup Scheduled</label>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($return->pickup_scheduled_date)->format('M d, Y') }}</p>
                        </div>
                        @endif
                        @if($return->item_received_date)
                        <div>
                            <label class="text-sm text-gray-500">Item Received</label>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($return->item_received_date)->format('M d, Y') }}</p>
                        </div>
                        @endif
                        @if($return->refund_processed_date)
                        <div>
                            <label class="text-sm text-gray-500">Refund Processed</label>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($return->refund_processed_date)->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <button class="close-image-btn" onclick="closeImageModal()">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Return Image">
    </div>

    <script>
        // Image Modal Functions
        function openImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        
        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('open');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
        
        // Close modal when clicking outside image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
        
        // Action Functions
        function contactCustomer() {
            const email = '{{ $return->customer_email }}';
            if (email) {
                window.location.href = `mailto:${email}?subject=Regarding Return Request RET-{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}`;
            } else {
                alert('Customer email not available');
            }
        }
        
        function generateLabel() {
            // In a real application, this would generate a shipping label
            alert('Shipping label generation feature would be implemented here.');
        }
    </script>
    <x-vendor.mobile-nav />
</body>
</html>