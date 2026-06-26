<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Return | Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        .timeline-step { position: relative; padding-left: 40px; margin-bottom: 30px; }
        .timeline-step:before { 
            content: ''; position: absolute; left: 0; top: 0; 
            width: 24px; height: 24px; border-radius: 50%; 
            border: 3px solid #e5e7eb; background: white;
        }
        .timeline-step.completed:before { 
            border-color: #10b981; background: #10b981; 
        }
        .timeline-step.current:before { 
            border-color: #3b82f6; background: white;
            animation: pulse 2s infinite;
        }
        .timeline-step:after {
            content: ''; position: absolute; left: 11px; top: 24px;
            width: 2px; height: calc(100% + 6px); background: #d1d5db;
        }
        .timeline-step:last-child:after { display: none; }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 sm:p-6 max-w-6xl">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Track Return Request</h1>
                        <p class="opacity-90">Return ID: RET-{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <a href="{{ route('customer.returns.index') }}" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg w-full sm:w-auto text-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Returns
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-8">
                <!-- Status Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-xl p-5">
                        <p class="text-sm text-blue-600 mb-1">Current Status</p>
                        <p class="text-xl font-bold text-gray-800 capitalize">{{ $return->status }}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-5">
                        <p class="text-sm text-green-600 mb-1">Refund Amount</p>
                        <p class="text-xl font-bold text-gray-800">${{ number_format($return->refund_amount, 2) }}</p>
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

                <!-- Progress Timeline -->
                <div class="mb-12">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Return Process</h2>
                    
                    @php
                        $steps = [
                            'request_submitted' => ['Request Submitted', 'Your return request has been submitted'],
                            'under_review' => ['Under Review', 'Vendor is reviewing your request'],
                            'approved' => ['Request Approved', 'Return request approved by vendor'],
                            'pickup_scheduled' => ['Pickup Scheduled', 'Pickup has been scheduled'],
                            'pickup_completed' => ['Pickup Completed', 'Item picked up for return'],
                            'item_received' => ['Item Received', 'Vendor received the returned item'],
                            'quality_check' => ['Quality Check', 'Item undergoing quality inspection'],
                            'refund_processing' => ['Refund Processing', 'Refund being processed'],
                            'refunded' => ['Refunded', 'Refund completed successfully'],
                            'completed' => ['Completed', 'Return process completed']
                        ];
                        
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
                            if ($track->step === 'quality_check') $currentStep = 'quality_check';
                            if ($track->step === 'refund_processing') $currentStep = 'refund_processing';
                            if ($track->step === 'refunded') $currentStep = 'refunded';
                            if ($track->step === 'completed') $currentStep = 'completed';
                        }
                        
                        $currentIndex = array_search($currentStep, $stepKeys);
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
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
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
                        <button class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 text-center justify-center flex items-center">
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