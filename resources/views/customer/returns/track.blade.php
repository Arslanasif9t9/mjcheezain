<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Return | MJ Cheezain</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-customer.theme />
    <style>
        .timeline-step { position: relative; padding-left: 40px; margin-bottom: 30px; }
        .timeline-step:before { 
            content: ''; position: absolute; left: 0; top: 0; 
            width: 24px; height: 24px; border-radius: 50%; 
            border: 3px solid #e5e7eb; background: white;
            z-index: 1;
        }
        .timeline-step.completed:before { 
            border-color: #10b981; background: #10b981; 
        }
        .timeline-step.current:before { 
            border-color: #E85D85; background: white;
            animation: pulse 2s infinite;
        }
        .timeline-step:after {
            content: ''; position: absolute; left: 11px; top: 24px;
            width: 2px; height: calc(100% + 6px); background: #d1d5db;
            z-index: 0;
        }
        .timeline-step.completed:after {
            background: #10b981;
        }
        .timeline-step:last-child:after { display: none; }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(232, 93, 133, 0.5); }
            50% { box-shadow: 0 0 0 10px rgba(232, 93, 133, 0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 sm:p-6 max-w-6xl">
        <div class="app-card overflow-hidden">
            <!-- Header -->
            <div class="brand-gradient p-4 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Track Return Request</h1>
                        <p class="opacity-90">Return ID: {{ $return->return_number ?? ('RET-' . str_pad($return->id, 6, '0', STR_PAD_LEFT)) }}</p>
                    </div>
                    <a href="{{ route('customer.returns.index') }}" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg w-full sm:w-auto text-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Returns
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-8">
                @php
                    $statusLabels = [
                        'pending' => 'Pending',
                        'vendor_reviewed' => 'Vendor Reviewed',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'processing' => 'Return / Pickup In Progress',
                        'received' => 'Product Received — Under Quality Check',
                        'qc_failed' => 'Quality Check Failed — Product Being Returned To You',
                        'refunded' => 'Refund Processed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ];
                @endphp

                <!-- Status Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="brand-gradient-soft rounded-xl p-5 border border-pink-100">
                        <p class="text-sm text-brand mb-1 font-semibold">Current Status</p>
                        <p class="text-xl font-bold text-gray-800">{{ $statusLabels[$return->status] ?? ucfirst($return->status) }}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-5">
                        <p class="text-sm text-green-600 mb-1">Refund Amount</p>
                        <p class="text-xl font-bold text-gray-800">Rs. {{ number_format($return->refund_amount, 2) }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-5">
                        <p class="text-sm text-purple-600 mb-1">Product</p>
                        <p class="text-xl font-bold text-gray-800 truncate">{{ $return->product_name }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-xl p-5">
                        <p class="text-sm text-yellow-600 mb-1">Request Date</p>
                        <p class="text-xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($return->created_at)->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Quality-check / refund banner -->
                @if($return->status === 'received')
                    <div class="mb-8 bg-orange-50 border border-orange-200 rounded-xl p-4 sm:p-6">
                        <h3 class="font-bold text-orange-800 mb-1"><i class="fas fa-magnifying-glass mr-2"></i> Product Received — Under Quality Check</h3>
                        <p class="text-orange-700 text-sm">We've received your returned item and our team is inspecting it. You'll be notified as soon as the quality check is complete.</p>
                    </div>
                @elseif($return->status === 'qc_failed')
                    <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-4 sm:p-6">
                        <h3 class="font-bold text-red-800 mb-1"><i class="fas fa-triangle-exclamation mr-2"></i> Quality Check Failed — Product Being Returned To You</h3>
                        <p class="text-red-700 text-sm">Our team inspected the returned item and it didn't pass quality check, so this return has been rejected — no refund will be issued and the product is being sent back to you.</p>
                    </div>
                @elseif(in_array($return->status, ['refunded', 'completed']))
                    <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-4 sm:p-6">
                        <h3 class="font-bold text-green-800 mb-1"><i class="fas fa-circle-check mr-2"></i> Refund Processed</h3>
                        <p class="text-green-700 text-sm">Your refund has been processed. It may take a few business days to reflect depending on your payment method.</p>
                    </div>
                @endif

                <!-- Progress Timeline -->
                <div class="mb-12">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Return Process</h2>
                    
                    @php
                        $steps = [
                            'request_submitted' => ['Request Submitted', 'Your return request has been submitted'],
                            'under_review' => ['Under Review', 'Vendor/Admin is reviewing your request'],
                            'approved' => ['Request Approved', 'Return request approved by admin'],
                            'pickup_scheduled' => ['Pickup Scheduled', 'Pickup has been scheduled'],
                            'pickup_completed' => ['Pickup Completed', 'Item picked up for return'],
                            'item_received' => ['Product Received — Under Quality Check', 'We received your item and it is being inspected'],
                        ];

                        // The outcome after quality check branches: pass -> refunded, fail ->
                        // qc_failed. Only the branch that actually happened is shown, so the
                        // timeline never shows both a refund and a rejection for the same return.
                        if ($return->status === 'qc_failed') {
                            $steps['qc_failed'] = ['Quality Check Failed', 'Product is being sent back to you — no refund'];
                        } else {
                            $steps['refund_processing'] = ['Refund Processing', 'Refund being processed'];
                            $steps['refunded'] = ['Refunded', 'Refund completed successfully'];
                            $steps['completed'] = ['Completed', 'Return process completed'];
                        }

                        $currentStep = 'request_submitted';
                        $stepKeys = array_keys($steps);

                        // Find current step from tracking
                        foreach ($tracking as $track) {
                            if ($track->step === 'request_submitted') $currentStep = 'request_submitted';
                            if ($track->step === 'under_review') $currentStep = 'under_review';
                            if ($track->step === 'approved') $currentStep = 'approved';
                            if ($track->step === 'pickup_scheduled') $currentStep = 'pickup_scheduled';
                            if ($track->step === 'pickup_completed') $currentStep = 'pickup_completed';
                            if ($track->step === 'item_received') $currentStep = 'item_received';
                            if ($track->step === 'qc_failed') $currentStep = 'qc_failed';
                            if ($track->step === 'quality_check') $currentStep = 'item_received';
                            if ($track->step === 'refund_processing') $currentStep = 'refund_processing';
                            if ($track->step === 'refunded') $currentStep = 'refunded';
                            if ($track->step === 'completed') $currentStep = 'completed';
                        }

                        $currentIndex = array_search($currentStep, $stepKeys);
                        if ($currentIndex === false) $currentIndex = 0;
                    @endphp
                    
                    <div class="relative">
                        @foreach($steps as $key => $step)
                            @php
                                $isCompleted = array_search($key, $stepKeys) <= $currentIndex;
                                $isCurrent = $key === $currentStep;
                                $stepClass = $isCurrent ? 'current' : ($isCompleted ? 'completed' : '');
                                $stepTime = $tracking->where('step', $key)->first()->created_at ?? null;
                            @endphp
                            
                            <div class="timeline-step {{ $stepClass }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $step[0] }}</h3>
                                        <p class="text-gray-600 mt-1">{{ $step[1] }}</p>
                                        @if($stepTime)
                                            <p class="text-sm text-gray-500 mt-2">
                                                {{ \Carbon\Carbon::parse($stepTime)->format('M d, Y h:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                    @if($isCurrent)
                                        <span class="bg-pink-100 text-[#E85D85] px-3 py-1 rounded-full text-sm font-medium">
                                            Current
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Images Section -->
                @if($images->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Uploaded Images</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($images as $image)
                            <img src="{{ Storage::url($image->image_path) }}" 
                                 class="w-full h-48 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                 onclick="openImageModal(this.src)">
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Video Section -->
                @if(!empty($return->return_video))
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Uploaded Video</h2>
                    <video controls class="w-full max-w-md rounded-lg border border-gray-200">
                        <source src="{{ asset('storage/' . $return->return_video) }}">
                    </video>
                </div>
                @endif

                <!-- Action Buttons -->
                @if($return->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-6">
                    <h3 class="font-bold text-yellow-800 mb-3"><i class="fas fa-exclamation-circle mr-2"></i> Action Required</h3>
                    <p class="text-yellow-700 mb-4">Your return request is pending vendor approval. You can cancel it if needed.</p>
                    <button onclick="cancelReturn({{ $return->id }})" 
                            class="w-full sm:w-auto px-5 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 text-center justify-center flex items-center">
                        <i class="fas fa-times mr-2"></i> Cancel Return Request
                    </button>
                </div>
                @endif

                <!-- Support Section -->
                <div class="mt-8 bg-gray-50 rounded-xl p-4 sm:p-6">
                    <h3 class="font-bold text-gray-800 mb-3"><i class="fas fa-headset mr-2"></i> Need Help?</h3>
                    <p class="text-gray-600 mb-4">If you have questions about your return, contact our support team.</p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button class="w-full sm:w-auto px-5 py-2.5 brand-gradient brand-shadow text-white rounded-full font-semibold hover:opacity-90 text-center justify-center flex items-center">
                            <i class="fas fa-comment-dots mr-2"></i> Chat Support
                        </button>
                        <button class="w-full sm:w-auto px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 text-center justify-center flex items-center">
                            <i class="fas fa-phone mr-2"></i> Call Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 p-4">
        <div class="relative max-w-4xl w-full">
            <button onclick="closeImageModal()" 
                    class="absolute top-4 right-4 bg-white/20 hover:bg-white/30 text-white w-10 h-10 rounded-full">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" class="w-full rounded-lg">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }
        
        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
        }
        
        async function cancelReturn(returnId) {
            if (!confirm('Are you sure you want to cancel this return request?')) return;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            try {
                const response = await fetch(`/customer/returns/cancel/${returnId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Return request cancelled successfully!');
                    window.location.reload();
                } else {
                    throw new Error(result.message || 'Cancellation failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error cancelling return: ' + error.message);
            }
        }
    </script>
</body>
</html>