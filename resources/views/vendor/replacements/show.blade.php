<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/page-loader.js') }}?v={{ @filemtime(public_path('js/page-loader.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Replacement Details | Vendor Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ @filemtime(public_path('css/tailwind.css')) ?: 1 }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Progress bar (used by the Replacement Progress card) */
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #e5e7eb;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
            transition: width 0.5s ease;
        }
    </style>
</head>
<body style="background-color: #FFF6F0;">
    <x-vendor.app-header
        title="Replace #{{ str_pad($replacement->id, 6, '0', STR_PAD_LEFT) }}"
        subtitle="Replacement request details"
        back="{{ route('vendor.replacements.index') }}" />

    <div class="container mx-auto p-4 md:p-6 pb-28 md:pb-8 page-enter">
        <div class="bg-transparent shadow-none p-0 md:bg-white md:rounded-2xl md:shadow-lg md:p-6">
            <!-- Header (desktop only — mobile uses the app header above) -->
            <div class="hidden md:flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Replacement Request Details</h1>
                    <p class="text-gray-600">ID: REP-{{ str_pad($replacement->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <a href="{{ route('vendor.replacements.index') }}" 
                   class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Customer & Product Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Information -->
                    <div class="app-card bg-white p-5 md:p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-user text-[#E85D85]"></i> Customer Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Customer Name</p>
                                <p class="font-medium">{{ $replacement->customer_first_name }} {{ $replacement->customer_last_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium">{{ $replacement->customer_email }}</p>
                            </div>
                            @if($replacement->customer_phone)
                            <div>
                                <p class="text-sm text-gray-500">Phone</p>
                                <p class="font-medium">{{ $replacement->customer_phone }}</p>
                            </div>
                            @endif
                            @if($order)
                            <div>
                                <p class="text-sm text-gray-500">Original Order</p>
                                <p class="font-medium text-[#E85D85]">ORD-{{ $order->id }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="app-card bg-white p-5 md:p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-box text-[#E85D85]"></i> Product Information
                        </h2>
                        <div class="flex items-start gap-4">
                            @php
                                $productImage = DB::table('vendor_product_images')
                                    ->where('product_id', $replacement->product_id)
                                    ->where('is_primary', 1)
                                    ->first();
                            @endphp
                            @if($productImage)
                                <img src="{{ asset('storage/vendor/products/images/' . $productImage->image_path) }}" 
                                     class="w-24 h-24 rounded-xl object-cover border border-gray-200">
                            @endif
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">{{ $replacement->product_name }}</h3>
                                <p class="text-gray-600 mb-2">Product ID: {{ $replacement->product_id }}</p>
                                <div class="flex gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Reason</p>
                                        <p class="font-medium capitalize">{{ str_replace('_', ' ', $replacement->reason) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Request Date</p>
                                        <p class="font-medium">{{ \Carbon\Carbon::parse($replacement->created_at)->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Replacement Courier Cost</p>
                                        <p class="font-medium">
                                            @if($replacement->courier_paid_by === 'vendor')
                                                <span class="text-red-600">Vendor pays</span>
                                            @elseif($replacement->courier_paid_by === 'customer')
                                                <span class="text-green-600">Customer pays</span>
                                            @else
                                                <span class="text-gray-400">Not determined</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($replacement->total_amount_payable)
                                    <div>
                                        <p class="text-sm text-gray-500">Total Payable by Customer</p>
                                        <p class="font-medium text-[#E85D85]">Rs. {{ number_format($replacement->total_amount_payable, 2) }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($replacement->details)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500 mb-2">Customer's Notes</p>
                            <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $replacement->details }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Vendor Comment -->
                    <div class="app-card bg-white p-5 md:p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-comment text-[#E85D85]"></i> Your Comment
                        </h2>
                        <p class="text-xs text-gray-500 mb-3">
                            You can only share your opinion on this request — MJ Cheezain Admin sees your
                            comment together with the customer's request and makes the final approve/reject decision.
                        </p>
                        @if($replacement->vendor_comment)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-800">{{ $replacement->vendor_comment }}</p>
                                @if($replacement->vendor_commented_at)
                                    <p class="text-xs text-gray-400 mt-2">
                                        Commented on {{ \Carbon\Carbon::parse($replacement->vendor_commented_at)->format('M d, Y h:i A') }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-400 text-sm">You haven't commented on this replacement yet.</p>
                        @endif
                    </div>

                    <!-- Tracking Timeline -->
                    <div class="app-card bg-white p-5 md:p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-history text-[#E85D85]"></i> Tracking History
                        </h2>
                        
                        <div class="space-y-4">
                            @forelse($tracking as $track)
                            <div class="timeline-item">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $track->step)) }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $track->description }}</p>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($track->created_at)->format('M d, h:i A') }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No tracking history available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column: Status & Actions -->
                <div class="space-y-6">
                    <!-- Status Summary -->
                    <div class="bg-gradient-to-br from-pink-50 to-indigo-50 border border-pink-100 rounded-xl p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Status Summary</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Current Status</p>
                                <span class="badge {{ [
                                    'pending' => 'bg-yellow-500',
                                    'approved' => 'bg-green-500',
                                    'processing' => 'bg-purple-500',
                                    'completed' => 'bg-teal-500',
                                    'cancelled' => 'bg-red-500',
                                    'rejected' => 'bg-red-500'
                                ][$replacement->status] ?? 'bg-gray-500' }} text-white px-3 py-1.5 rounded-full font-medium">
                                    {{ ucfirst($replacement->status) }}
                                </span>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Current Step</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $replacement->current_step)) }}</p>
                            </div>
                            
                            @if($replacement->tracking_number)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tracking Number</p>
                                <p class="font-medium text-[#E85D85]">{{ $replacement->tracking_number }}</p>
                            </div>
                            @endif
                            
                            @if($replacement->estimated_delivery_date)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Estimated Delivery</p>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($replacement->estimated_delivery_date)->format('M d, Y') }}</p>
                            </div>
                            @endif
                            
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($replacement->updated_at)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="app-card bg-white p-5 md:p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h2>
                        
                        <div class="space-y-3">
                            <button onclick="updateStatus()"
                                    class="w-full px-4 py-3 brand-gradient brand-shadow text-white rounded-full font-semibold hover:opacity-90 flex items-center justify-center gap-2 border-0">
                                <i class="fas fa-comment"></i> Add Comment
                            </button>

                            <a href="mailto:{{ $replacement->customer_email }}"
                               class="w-full px-4 py-3 bg-pink-100 text-[#C94A72] rounded-full font-medium hover:bg-pink-200 flex items-center justify-center gap-2">
                                <i class="fas fa-envelope"></i> Email Customer
                            </a>

                            @if($replacement->tracking_number)
                            <a href="https://www.trackingmore.com/track.php?num={{ $replacement->tracking_number }}"
                               target="_blank"
                               class="w-full px-4 py-3 bg-purple-100 text-purple-700 rounded-full font-medium hover:bg-purple-200 flex items-center justify-center gap-2">
                                <i class="fas fa-truck"></i> Track Shipment
                            </a>
                            @endif

                            <button onclick="printPage()"
                                    class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-full font-medium hover:bg-gray-200 flex items-center justify-center gap-2 border-0">
                                <i class="fas fa-print"></i> Print Details
                            </button>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="app-card bg-white p-5 md:p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Replacement Progress</h2>
                        
                        <div class="space-y-2">
                            @php
                                $steps = [
                                    'request_submitted' => 'Request Submitted',
                                    'request_approved' => 'Approved',
                                    'shipped_to_vendor' => 'Original Shipped',
                                    'received_by_vendor' => 'Received',
                                    'replacement_verified' => 'Verified',
                                    'replacement_processing' => 'Processing',
                                    'replacement_shipped' => 'Shipped',
                                    'replacement_delivered' => 'Delivered'
                                ];
                                
                                $currentStep = $replacement->current_step;
                                $stepKeys = array_keys($steps);
                                $currentIndex = array_search($currentStep, $stepKeys);
                                $progress = $currentIndex !== false ? (($currentIndex + 1) / count($steps)) * 100 : 0;
                            @endphp
                            
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $progress }}%"></div>
                            </div>
                            
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>{{ round($progress) }}% Complete</span>
                                <span>Step {{ $currentIndex + 1 }} of {{ count($steps) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStatus() {
            window.opener ? window.opener.openStatusModal({{ $replacement->id }}, '{{ $replacement->status }}') : 
                           window.location.href = "{{ route('vendor.replacements.index') }}";
        }
        
        function printPage() {
            window.print();
        }
    </script>
    <x-vendor.mobile-nav />
</body>
</html>