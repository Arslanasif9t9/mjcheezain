<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Multivendor Platform</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        .order-card {
            transition: all 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .order-status-processing { border-left: 4px solid #3b82f6; }
        .order-status-shipped { border-left: 4px solid #f59e0b; }
        .order-status-delivered { border-left: 4px solid #10b981; }
        .order-status-cancelled { border-left: 4px solid #ef4444; }
        .order-status-returned { border-left: 4px solid #8b5cf6; }
        .tab-active {
            border-bottom: 3px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
        }
        .sidebar-item.active {
            background-color: #f3f4f6;
            border-right: 3px solid #3b82f6;
            color: #3b82f6;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .order-card {
            animation: fadeIn 0.3s ease-out forwards;
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Add this to your existing styles */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        /* Star Rating Styles */
        .fa-star {
            transition: all 0.2s ease;
        }

        .fa-star.text-yellow-500 {
            color: #f59e0b;
        }

        .fa-star.text-yellow-400 {
            color: #fbbf24;
        }

        .fa-star.text-yellow-300 {
            color: #fcd34d;
        }

        /* Modal Animation */
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #rateProductModal > div,
        #replaceProductModal > div,
        #returnProductModal > div {
            animation: modalFadeIn 0.3s ease-out;
        }
        
        /* Horizontal Timeline Styles */
        .horizontal-timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-step {
            position: relative;
            display: inline-block;
            width: 150px;
            text-align: center;
            z-index: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 1.25rem;
            border: 3px solid #e5e7eb;
            background: white;
            transition: all 0.3s ease;
        }

        .timeline-step.completed .step-icon {
            border-color: #3b82f6;
            background: #3b82f6;
            color: white;
        }

        .timeline-step.current .step-icon {
            border-color: #3b82f6;
            background: white;
            color: #3b82f6;
            animation: pulse 2s infinite;
        }

        .timeline-step .step-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .timeline-step.completed .step-label {
            color: #3b82f6;
        }

        .timeline-step.current .step-label {
            color: #1d4ed8;
            font-weight: 700;
        }

        .timeline-connector {
            position: absolute;
            top: 25px;
            left: 75px;
            width: 75px;
            height: 3px;
            background: #e5e7eb;
            z-index: 0;
        }

        .timeline-step.completed + .timeline-step .timeline-connector {
            background: #3b82f6;
        }

        /* Status Badge Styles */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-badge.processing {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-badge.completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-badge.cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-badge.return-requested {
            background-color: #ffedd5;
            color: #9a3412;
        }

        /* Modal Enhancements */
        .modal-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .info-card.return {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .action-card {
            background-color: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .action-card.return {
            background-color: #ffedd5;
            border: 1px solid #fdba74;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            background-color: #f9fafb;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 0.875rem;
            color: #111827;
            font-weight: 600;
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: white;
            color: #4b5563;
            border: 1px solid #d1d5db;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #f9fafb;
        }
        
        /* Modal Scrollbar Styling */
        #replacementTrackingModal > div::-webkit-scrollbar,
        #returnTrackingModal > div::-webkit-scrollbar {
            width: 8px;
        }

        #replacementTrackingModal > div::-webkit-scrollbar-track,
        #returnTrackingModal > div::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        #replacementTrackingModal > div::-webkit-scrollbar-thumb,
        #returnTrackingModal > div::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        #replacementTrackingModal > div::-webkit-scrollbar-thumb:hover,
        #returnTrackingModal > div::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Timeline Animation */
        @keyframes stepComplete {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .timeline-step.completed .step-icon {
            animation: stepComplete 0.5s ease;
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-customer.sidebar :basic_info="$basic_info"/>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <!-- Left side - Mobile menu and title -->
                <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-500 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Orders</h1>
                </div>

                <!-- Center - Search bar -->
                <div class="hidden flex-1 max-w-md mx-4">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search..."
                            class="w-full py-2 pl-4 pr-10 text-sm bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                        <button class="absolute right-3 top-2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Right side - Icons and user menu -->
                <div class="hidden flex items-center space-x-4">
                    <!-- User dropdown -->
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Order Filter Tabs -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="nav-tabs flex -mb-px overflow-x-auto">
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm tab-active border-blue-600 text-blue-600">
                                All Orders ({{ count($orders) }})
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Processing (2)
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Shipped (1)
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Delivered (4)
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Cancelled (1)
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="relative mb-4 md:mb-0 md:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search orders...">
                        </div>
                        <div class="flex space-x-3">
                            <div class="relative">
                                <select class="appearance-none bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>Last 30 days</option>
                                    <option>Last 3 months</option>
                                    <option>Last 6 months</option>
                                    <option>Last year</option>
                                    <option>All time</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div class="order-card bg-white rounded-lg shadow order-status-processing">
                            <div class="p-5">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="mb-4 md:mb-0">
                                        <div class="flex items-center">
                                            <h3 class="text-lg font-medium text-gray-900">Order #ORD-{{ $order->id }}</h3>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Placed on {{ date('F d, Y', strtotime($order->order_date)) }}</p>
                                    </div>
                                    <div class="flex items-center">
                                        <p class="text-sm text-gray-900">{{ $order->quantity }} items &nbsp;&nbsp;</p>
                                        <p class="text-lg font-semibold text-gray-900">${{ $order->total_amount }}</p>
                                        <button class="ml-4 p-2 text-gray-400 hover:text-gray-500">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                                @php
                                    $carts = DB::table('carts')
                                        ->where('order_id', $order->id)
                                        ->join('vendor_products', 'carts.product_id', '=', 'vendor_products.id')
                                        ->join('vendor_product_images', function($join) {
                                            $join->on('carts.product_id', '=', 'vendor_product_images.product_id')
                                                ->where('vendor_product_images.is_primary', 1);
                                        })
                                        ->select('carts.*', 'vendor_products.name as product_name', 'vendor_product_images.image_path as img')
                                        ->get();
                                @endphp
                                <div id="order-carts">
                                    @foreach ($carts as $cart)
                                        @php
                                            $sColor = 'blue';
                                            $timelineStatus = 'Order Placed';
                                            
                                            switch ($cart->status) {
                                                case 'processing':
                                                    $sColor = 'purple';
                                                    $timelineStatus = 'Processing';
                                                    break;
                                                case 'shipping':
                                                    $sColor = 'yellow';
                                                    $timelineStatus = 'Shipped';
                                                    break;
                                                case 'cancelled':
                                                    $sColor = 'red';
                                                    $timelineStatus = 'Cancelled';
                                                    break;
                                                case 'delivered':
                                                    $sColor = 'green';
                                                    $timelineStatus = 'Delivered';
                                                    break;
                                                default:
                                                    $sColor = 'blue';
                                                    $timelineStatus = 'Order Placed';
                                                    break;
                                            }
                                            
                                            // Check if product is already rated
                                            $isRated = false;
                                            if (isset($cart->id)) {
                                                $isRated = DB::table('product_ratings')
                                                    ->where('product_id', $cart->product_id)
                                                    ->where('customer_id', auth()->id())
                                                    ->where('order_id', $order->id)
                                                    ->exists();
                                            }
                                            
                                            // Check if replacement exists for this product in this order
                                            $hasReplacement = DB::table('replacement_requests')
                                                ->where('product_id', $cart->product_id)
                                                ->where('customer_id', auth()->id())
                                                ->where('order_id', $order->id)
                                                ->where('status', '!=', 'rejected')
                                                ->exists();
                                                
                                            $replacementInfo = null;
                                            if ($hasReplacement) {
                                                $replacementInfo = DB::table('replacement_requests')
                                                    ->where('product_id', $cart->product_id)
                                                    ->where('customer_id', auth()->id())
                                                    ->where('order_id', $order->id)
                                                    ->where('status', '!=', 'rejected')
                                                    ->first();
                                            }
                                            
                                            // Check if return request exists for this product in this order
                                            $hasReturnRequest = DB::table('return_requests')
                                                ->where('product_id', $cart->product_id)
                                                ->where('customer_id', auth()->id())
                                                // ->where('order_id', $order->id)
                                                ->where('status', '!=', 'rejected')
                                                ->exists();

                                                
                                            $returnRequestInfo = null;
                                            if ($hasReturnRequest) {
                                                $returnRequestInfo = DB::table('return_requests')
                                                ->where('product_id', $cart->product_id)
                                                ->where('customer_id', auth()->id())
                                                // ->where('order_id', $order->id)
                                                ->where('status', '!=', 'rejected')
                                                ->first();
                                            }
                                            // dd($returnRequestInfo);
                                        @endphp
                                        
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="flex flex-col md:flex-row md:items-center">
                                                <div class="flex-1 mb-4 md:mb-0">
                                                    <h4 class="text-sm font-medium text-gray-900 mb-2">
                                                        Items 
                                                        <span class="ml-2 badge bg-{{ $sColor }}-100 text-{{ $sColor }}-800">{{ $timelineStatus }}</span>
                                                        @if($hasReplacement)
                                                            <span class="ml-2 badge bg-purple-100 text-purple-800">Replacement Requested</span>
                                                        @endif
                                                        @if($hasReturnRequest)
                                                            <span class="ml-2 badge bg-orange-100 text-orange-800">Return Requested</span>
                                                        @endif
                                                    </h4>
                                                    
                                                    <div class="flex items-center space-x-3">
                                                        <img src="{{ asset("storage/vendor/products/images/$cart->img") }}" alt="Product" class="w-16 h-16 rounded-lg object-cover">
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ $cart->product_name }}</p>
                                                            <p class="text-sm text-gray-500">Quantity: {{ $cart->quantity }} • Total Price: ${{ $cart->price * $cart->quantity }}</p>
                                                            @if($hasReplacement && $replacementInfo->current_step)
                                                                <p class="text-xs text-purple-600 mt-1">
                                                                    Replacement Status: {{ ucfirst(str_replace('_', ' ', $replacementInfo->current_step)) }}
                                                                </p>
                                                            @endif
                                                            {{-- @if($hasReturnRequest && $returnRequestInfo->current_step) --}}
                                                            @if($hasReturnRequest && $returnRequestInfo->status)
                                                                <p class="text-xs text-orange-600 mt-1">
                                                                    Return Status: {{ ucfirst(str_replace('_', ' ', $returnRequestInfo->status)) }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="md:ml-6 md:pl-6 md:border-l md:border-gray-200 mt-4 md:mt-0">
                                                    <div class="flex space-x-3">
                                                        @if($cart->status == 'delivered')
                                                            <!-- For delivered products -->
                                                            @if($hasReplacement && $replacementInfo)
                                                                <!-- Show replacement tracking button -->
                                                                <button 
                                                                    class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white border-0 rounded-lg text-sm font-semibold hover:shadow-lg hover:from-purple-600 hover:to-pink-600 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center track-replacement-btn"
                                                                    data-replacement-id="{{ $replacementInfo->id }}"
                                                                >
                                                                    <i class="fas fa-truck-fast mr-2"></i>Track Replacement
                                                                </button>
                                                                
                                                                @if($replacementInfo->status == 'completed')
                                                                    <!-- If replacement is completed, show rate button again -->
                                                                    <button 
                                                                        class="px-4 py-2 bg-blue-500 text-white border border-blue-600 rounded-lg text-sm font-medium hover:bg-blue-600 flex items-center rate-product-btn"
                                                                        data-product-id="{{ $cart->product_id }}"
                                                                        data-product-name="{{ $cart->product_name }}"
                                                                        data-order-id="{{ $order->id }}"
                                                                        data-is-rated="{{ $isRated ? 'true' : 'false' }}"
                                                                    >
                                                                        <i class="fas fa-star mr-2"></i>{{ $isRated ? 'View Rating' : 'Rate' }}
                                                                    </button>
                                                                @endif
                                                            @elseif($hasReturnRequest)
                                                                <!-- Show view return request button -->
                                                                <a href="/customer/returns/track/{{ $returnRequestInfo->id }}" target="_blank">
                                                                    <button 
                                                                        class="px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white border-0 rounded-lg text-sm font-semibold hover:shadow-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center view-return-request-btn"
                                                                        data-return-id="{{ $returnRequestInfo->id }}"
                                                                    >
                                                                        <i class="fas fa-file-alt mr-2"></i>View Request
                                                                    </button>
                                                                </a>
                                                            @else
                                                                <!-- Original delivered product buttons -->
                                                                <button 
                                                                    class="px-4 py-2 bg-blue-500 text-white border border-blue-600 rounded-lg text-sm font-medium hover:bg-blue-600 flex items-center rate-product-btn"
                                                                    data-product-id="{{ $cart->product_id }}"
                                                                    data-product-name="{{ $cart->product_name }}"
                                                                    data-order-id="{{ $order->id }}"
                                                                    data-is-rated="{{ $isRated ? 'true' : 'false' }}"
                                                                >
                                                                    <i class="fas fa-star mr-2"></i>{{ $isRated ? 'View Rating' : 'Rate' }}
                                                                </button>
                                                                
                                                                <button class="px-4 py-2 bg-yellow-500 text-white border border-yellow-600 rounded-lg text-sm font-medium hover:bg-yellow-600 flex items-center replace-product-btn"
                                                                        data-product-id="{{ $cart->product_id }}"
                                                                        data-order-id="{{ $order->id }}">
                                                                    <i class="fas fa-exchange-alt mr-2"></i>Replace
                                                                </button>
                                                                
                                                                <button class="px-4 py-2 bg-red-500 text-white border border-red-600 rounded-lg text-sm font-medium hover:bg-red-600 flex items-center return-product-btn"
                                                                        data-product-id="{{ $cart->product_id }}"
                                                                        data-order-id="{{ $order->id }}">
                                                                    <i class="fas fa-undo mr-2"></i>Return
                                                                </button>
                                                            @endif
                                                        @elseif($cart->status == 'cancelled')
                                                            <span class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg text-sm font-medium">
                                                                Cancelled
                                                            </span>
                                                        @else
                                                            <!-- For non-delivered products -->
                                                            <button 
                                                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center track-order-btn"
                                                                data-order-id="ORD-{{ $order->id }}"
                                                                data-order-status="{{ $timelineStatus }}"
                                                            >
                                                                <i class="fas fa-truck mr-2"></i>Track
                                                            </button>
                                                            <button class="px-4 py-2 bg-red-500 text-white border border-red-600 rounded-lg text-sm font-medium hover:bg-red-600 flex items-center cancel-order-btn"
                                                                    data-order-id="{{ $order->id }}"
                                                                    data-cart-id="{{ $cart->id }}">
                                                                <i class="fas fa-times mr-2"></i>Cancel
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </main>
        </div>
    </div>

    <!-- Tracking Modal -->
    <div id="trackingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Track Order #ORD-1234</h2>
                    <button onclick="closeTrackingModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Timeline -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <!-- Order Placed Step -->
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-shopping-cart text-gray-500"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600">Order Placed</p>
                        </div>
                        
                        <div class="h-1 flex-1 bg-gray-300"></div>
                        
                        <!-- Processing Step -->
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-cog text-gray-500"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600">Processing</p>
                        </div>
                        
                        <div class="h-1 flex-1 bg-gray-300"></div>
                        
                        <!-- Shipped Step -->
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-truck text-gray-500"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600">Shipped</p>
                        </div>
                        
                        <div class="h-1 flex-1 bg-gray-300"></div>
                        
                        <!-- Delivered Step -->
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-check text-gray-500"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600">Delivered</p>
                        </div>
                    </div>
                    
                    <!-- Status Message -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600" id="statusMessage"></p>
                                <p class="text-xs text-gray-500 mt-1" id="statusDetails"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estimated Delivery -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-blue-600">
                            <i class="fas fa-clock mr-2"></i>
                            Estimated delivery: <span id="estimatedDelivery">Nov 20, 2023</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rate Product Modal -->
    <div id="rateProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Rate Product</h2>
                    <button onclick="closeRateModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="ratingContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Replace Product Modal -->
    <div id="replaceProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Replace Product</h2>
                    <button onclick="closeReplaceModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="replaceContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Return Product Modal -->
    <div id="returnProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Return Product</h2>
                    <button onclick="closeReturnModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="returnContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Replacement Tracking Modal -->
    <div id="replacementTrackingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Replacement Tracking</h2>
                        <p class="text-sm text-gray-600 mt-1">Track your replacement request in real-time</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="refreshTracking()" class="p-2 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button onclick="closeReplacementTrackingModal()" 
                                class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div id="replacementTrackingContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Return Request Tracking Modal -->
    <div id="returnTrackingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Return Request Tracking</h2>
                        <p class="text-sm text-gray-600 mt-1">Track your return request in real-time</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="refreshReturnTracking()" class="p-2 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button onclick="closeReturnTrackingModal()" 
                                class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div id="returnTrackingContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Rate Product Functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.rate-product-btn')) {
                const button = e.target.closest('.rate-product-btn');
                const productId = button.getAttribute('data-product-id');
                const productName = button.getAttribute('data-product-name');
                const orderId = button.getAttribute('data-order-id');
                const isRated = button.getAttribute('data-is-rated') === 'true';
                
                if (isRated) {
                    showViewRating(productId, productName, orderId);
                } else {
                    showRatingForm(productId, productName, orderId);
                }
            }
            
            if (e.target.closest('.replace-product-btn')) {
                const button = e.target.closest('.replace-product-btn');
                const productId = button.getAttribute('data-product-id');
                const orderId = button.getAttribute('data-order-id');
                showReplaceForm(productId, orderId);
            }
            
            if (e.target.closest('.return-product-btn')) {
                const button = e.target.closest('.return-product-btn');
                const productId = button.getAttribute('data-product-id');
                const orderId = button.getAttribute('data-order-id');
                showReturnOptions(productId, orderId);
            }
            
            if (e.target.closest('.cancel-order-btn')) {
                const button = e.target.closest('.cancel-order-btn');
                const orderId = button.getAttribute('data-order-id');
                const cartId = button.getAttribute('data-cart-id');
                cancelOrder(orderId, cartId);
            }
            
            if (e.target.closest('.track-replacement-btn')) {
                const button = e.target.closest('.track-replacement-btn');
                const replacementId = button.getAttribute('data-replacement-id');
                showReplacementTracking(replacementId);
            }
            
            // if (e.target.closest('.view-return-request-btn')) {
            //     const button = e.target.closest('.view-return-request-btn');
            //     const returnId = button.getAttribute('data-return-id');
            //     showReturnTracking(returnId);
            // }
        });

        // Show Rating Form
        function showRatingForm(productId, productName, orderId) {
            const modal = document.getElementById('rateProductModal');
            const content = document.getElementById('ratingContent');
            
            content.innerHTML = `
                <div>
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">${productName}</h3>
                        <p class="text-sm text-gray-600">How would you rate this product?</p>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex justify-center mb-4">
                            <div class="flex space-x-1" id="starRating">
                                ${Array(5).fill().map((_, i) => `
                                    <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400" 
                                    data-rating="${i + 1}"></i>
                                `).join('')}
                            </div>
                        </div>
                        <p class="text-center text-sm text-gray-600" id="ratingText">Tap to rate</p>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                        <textarea id="reviewComment" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                rows="4" 
                                placeholder="Share your experience with this product..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeRateModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button onclick="submitRating(${productId}, ${orderId})" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Submit Review
                        </button>
                    </div>
                </div>
            `;
            
            // Add star rating interaction
            const stars = content.querySelectorAll('#starRating .fa-star');
            let selectedRating = 0;
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    selectedRating = rating;
                    
                    // Update stars display
                    stars.forEach((s, index) => {
                        if (index < rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-500');
                        } else {
                            s.classList.remove('text-yellow-500');
                            s.classList.add('text-gray-300');
                        }
                    });
                    
                    // Update rating text
                    const ratingTexts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
                    document.getElementById('ratingText').textContent = ratingTexts[rating - 1] || 'Tap to rate';
                });
                
                // Hover effect
                star.addEventListener('mouseover', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    stars.forEach((s, index) => {
                        if (index < rating) {
                            s.classList.add('text-yellow-300');
                        }
                    });
                });
                
                star.addEventListener('mouseout', function() {
                    stars.forEach((s, index) => {
                        if (index < selectedRating) {
                            s.classList.remove('text-yellow-300');
                            s.classList.add('text-yellow-500');
                        } else {
                            s.classList.remove('text-yellow-300');
                            s.classList.add('text-gray-300');
                        }
                    });
                });
            });
            
            // Store data for submission
            window.currentRatingData = {
                productId: productId,
                productName: productName,
                orderId: orderId
            };
            
            modal.classList.remove('hidden');
        }

        // Show View Rating (when already rated)
        function showViewRating(productId, productName, orderId) {
            const modal = document.getElementById('rateProductModal');
            const content = document.getElementById('ratingContent');
            
            // Fetch existing rating via AJAX
            fetch(`/get-rating/${productId}/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = `
                        <div>
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">${productName}</h3>
                                <p class="text-sm text-gray-600">Your Rating</p>
                            </div>
                            
                            <div class="mb-6">
                                <div class="flex justify-center mb-4">
                                    <div class="flex space-x-1">
                                        ${Array(5).fill().map((_, i) => `
                                            <i class="fas fa-star text-3xl ${i < data.rating ? 'text-yellow-500' : 'text-gray-300'}"></i>
                                        `).join('')}
                                    </div>
                                </div>
                                <p class="text-center text-lg font-medium text-gray-900">${getRatingText(data.rating)}</p>
                            </div>
                            
                            ${data.comment ? `
                                <div class="mb-6 bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Your Review</h4>
                                    <p class="text-gray-600">${data.comment}</p>
                                </div>
                            ` : ''}
                            
                            <div class="flex justify-end">
                                <button onclick="closeRateModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                    Close
                                </button>
                            </div>
                        </div>
                    `;
                    
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching rating:', error);
                    alert('Error loading your rating. Please try again.');
                });
        }

        // Submit Rating Function
        function submitRating(productId, orderId) {
            const stars = document.querySelectorAll('#starRating .fa-star.text-yellow-500');
            const rating = stars.length;
            const comment = document.getElementById('reviewComment').value.trim();
            
            if (rating === 0) {
                alert('Please select a star rating');
                return;
            }
            
            // Check if this is a replacement rating
            const isReplacement = document.querySelector('.track-replacement-btn') !== null;
            
            // Prepare data
            const data = {
                product_id: productId,
                order_id: orderId,
                rating: rating,
                comment: comment,
                is_replacement: isReplacement,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            
            // Show loading
            const submitBtn = document.querySelector('#ratingContent button[onclick^="submitRating"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;
            
            // Send AJAX request
            fetch('/rate-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Update button state
                    const rateBtn = document.querySelector(`.rate-product-btn[data-product-id="${productId}"][data-order-id="${orderId}"]`);
                    if (rateBtn) {
                        rateBtn.innerHTML = '<i class="fas fa-star mr-2"></i>View Rating';
                        rateBtn.setAttribute('data-is-rated', 'true');
                    }
                    
                    // Update product rating in display if shown
                    const ratingElement = document.querySelector(`[data-rating-display="${productId}"]`);
                    if (ratingElement) {
                        ratingElement.textContent = result.newAverage.toFixed(1);
                    }
                    
                    // Show success message
                    alert('Thank you for your rating!');
                    closeRateModal();
                } else {
                    alert(result.message || 'Error submitting rating');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }

        // Show Replace Form
        function showReplaceForm(productId, orderId) {
            const modal = document.getElementById('replaceProductModal');
            const content = document.getElementById('replaceContent');
            
            content.innerHTML = `
                <div>
                    <div class="mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h3 class="font-medium text-blue-800 mb-2"><i class="fas fa-info-circle mr-2"></i>Replacement Process</h3>
                            <ul class="text-sm text-blue-700 space-y-1 ml-6 list-disc">
                                <li>Your request will be reviewed within 24 hours</li>
                                <li>If approved, a replacement will be shipped</li>
                                <li>You may need to return the original item</li>
                                <li>Shipping costs may apply</li>
                            </ul>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Replacement</label>
                            <select id="replaceReason" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a reason</option>
                                <option value="damaged">Product Damaged</option>
                                <option value="wrong_item">Wrong Item Received</option>
                                <option value="defective">Defective Product</option>
                                <option value="size">Wrong Size</option>
                                <option value="color">Wrong Color</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Details</label>
                            <textarea id="replaceDetails" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    rows="4" 
                                    placeholder="Please provide more details about why you need a replacement..."></textarea>
                        </div>
                        
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" id="agreeTerms" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                <span class="text-sm text-gray-700">I agree to the replacement terms and conditions</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeReplaceModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button onclick="submitReplaceRequest(${productId}, ${orderId})" class="px-4 py-2 bg-yellow-600 text-white rounded-lg text-sm font-medium hover:bg-yellow-700">
                            Submit Request
                        </button>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
        }

        // Show Return Options
        function showReturnOptions(productId, orderId) {
            const modal = document.getElementById('returnProductModal');
            const content = document.getElementById('returnContent');
            
            content.innerHTML = `
                <div>
                    <div class="mb-6">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <h3 class="font-medium text-red-800 mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Return Policy</h3>
                            <ul class="text-sm text-red-700 space-y-1 ml-6 list-disc">
                                <li>Returns must be initiated within 30 days of delivery</li>
                                <li>Items must be in original condition</li>
                                <li>Refunds will be processed within 7-10 business days</li>
                                <li>Original shipping costs are non-refundable</li>
                            </ul>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-6">Please choose the type of return you'd like to initiate:</p>
                        
                        <div class="space-y-4">
                            <button onclick="openReturnModal(${orderId}, ${productId})" 
                                    class="w-full p-4 border border-red-300 rounded-lg text-left hover:bg-red-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-undo text-red-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Return Item</h4>
                                        <p class="text-sm text-gray-600">Return this item for a refund</p>
                                    </div>
                                </div>
                            </button>
                            
                            <button onclick="initiateReturn(${productId}, ${orderId}, 'return_report')" 
                                    class="w-full p-4 border border-red-300 rounded-lg text-left hover:bg-red-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Return & Report</h4>
                                        <p class="text-sm text-gray-600">Return item and report an issue</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button onclick="closeReturnModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
        }

        function openReturnModal(orderId, productId) {
            window.open(`/customer/returns/create/${orderId}/${productId}`, '_blank');
        }

        // Helper Functions
        function getRatingText(rating) {
            const texts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
            return texts[rating - 1] || 'Good';
        }

        function closeRateModal() {
            document.getElementById('rateProductModal').classList.add('hidden');
            window.currentRatingData = null;
        }

        function closeReplaceModal() {
            document.getElementById('replaceProductModal').classList.add('hidden');
        }

        function closeReturnModal() {
            document.getElementById('returnProductModal').classList.add('hidden');
        }

        // Submit Replace Request
        function submitReplaceRequest(productId, orderId) {
            const reason = document.getElementById('replaceReason').value;
            const details = document.getElementById('replaceDetails').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;
            
            if (!reason) {
                alert('Please select a reason for replacement');
                return;
            }
            
            if (!agreeTerms) {
                alert('Please agree to the terms and conditions');
                return;
            }
            
            // Prepare data for AJAX request
            const data = {
                product_id: productId,
                order_id: orderId,
                reason: reason,
                details: details,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            
            fetch('/submit-replace-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Replacement request submitted successfully! We will contact you within 24 hours.');
                    closeReplaceModal();
                } else {
                    alert(result.message || 'Error submitting request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            });
        }

        // Refresh tracking data
        function refreshTracking() {
            const replacementId = document.querySelector('.track-replacement-btn')?.getAttribute('data-replacement-id');
            if (replacementId) {
                showReplacementTracking(replacementId);
            }
        }

        // Refresh return tracking data
        function refreshReturnTracking() {
            const returnId = document.querySelector('.view-return-request-btn')?.getAttribute('data-return-id');
            if (returnId) {
                showReturnTracking(returnId);
            }
        }

        // Initiate Return
        function initiateReturn(productId, orderId, type) {
            if (confirm(`Are you sure you want to ${type === 'return' ? 'return' : 'return and report'} this item?`)) {
                const data = {
                    product_id: productId,
                    order_id: orderId,
                    return_type: type,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                fetch('/initiate-return', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(`Return ${type === 'return' ? 'initiated' : 'and report submitted'} successfully!`);
                        closeReturnModal();
                        // Optionally update UI
                    } else {
                        alert(result.message || 'Error processing return');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please try again.');
                });
            }
        }

        // Cancel Order
        function cancelOrder(orderId, cartId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                const data = {
                    order_id: orderId,
                    cart_id: cartId,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                fetch('/cancel-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Order cancelled successfully!');
                        // Reload page or update UI
                        location.reload();
                    } else {
                        alert(result.message || 'Error cancelling order');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please try again.');
                });
            }
        }

        // Show Replacement Tracking
        function showReplacementTracking(replacementId) {
            const modal = document.getElementById('replacementTrackingModal');
            const content = document.getElementById('replacementTrackingContent');
            
            // Show loading
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-blue-500 mb-4"></i>
                    <p class="text-gray-600">Loading tracking information...</p>
                </div>
            `;
            
            modal.classList.remove('hidden');
            
            // Fetch replacement tracking data
            fetch(`/get-replacement-tracking/${replacementId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderReplacementTracking(data.replacement, data.trackingSteps);
                    } else {
                        content.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                                <p class="text-gray-600">Error loading tracking information</p>
                                <p class="text-sm text-gray-500">${data.message}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                            <p class="text-gray-600">Network error. Please try again.</p>
                        </div>
                    `;
                });
        }

        // Render Replacement Tracking with Horizontal Timeline
        function renderReplacementTracking(replacement, trackingSteps) {
            const content = document.getElementById('replacementTrackingContent');
            
            // Define all steps with their details
            const steps = [
                {
                    id: 'request_submitted',
                    label: 'Request Submitted',
                    icon: 'fa-paper-plane',
                    description: 'Your replacement request has been submitted',
                    color: 'blue'
                },
                {
                    id: 'request_approved',
                    label: 'Approved',
                    icon: 'fa-check-circle',
                    description: 'Request approved by vendor',
                    color: 'green'
                },
                {
                    id: 'shipped_to_vendor',
                    label: 'Original Shipped',
                    icon: 'fa-box',
                    description: 'You shipped the original item',
                    color: 'yellow'
                },
                {
                    id: 'received_by_vendor',
                    label: 'Vendor Received',
                    icon: 'fa-box-open',
                    description: 'Vendor received your item',
                    color: 'purple'
                },
                {
                    id: 'replacement_verified',
                    label: 'Verified',
                    icon: 'fa-clipboard-check',
                    description: 'Issue verified by vendor',
                    color: 'indigo'
                },
                {
                    id: 'replacement_processing',
                    label: 'Processing',
                    icon: 'fa-cog',
                    description: 'Replacement being prepared',
                    color: 'pink'
                },
                {
                    id: 'replacement_shipped',
                    label: 'Replacement Shipped',
                    icon: 'fa-shipping-fast',
                    description: 'New item shipped to you',
                    color: 'red'
                },
                {
                    id: 'replacement_delivered',
                    label: 'Delivered',
                    icon: 'fa-home',
                    description: 'Replacement delivered',
                    color: 'green'
                }
            ];
            
            // Find current step index
            const currentStepIndex = steps.findIndex(step => step.id === replacement.current_step);
            
            // Build horizontal timeline HTML
            let timelineHTML = `
                <div class="horizontal-timeline">
                    <div class="flex justify-between relative">
            `;
            
            steps.forEach((step, index) => {
                const isCompleted = index <= currentStepIndex;
                const isCurrent = index === currentStepIndex;
                const stepClass = isCurrent ? 'current' : (isCompleted ? 'completed' : '');
                const stepTime = trackingSteps[step.id] 
                    ? new Date(trackingSteps[step.id]).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
                    : '';
                
                timelineHTML += `
                    <div class="timeline-step ${stepClass}">
                        <div class="step-icon">
                            <i class="fas ${step.icon}"></i>
                        </div>
                        ${index < steps.length - 1 ? '<div class="timeline-connector"></div>' : ''}
                        <div class="step-label">${step.label}</div>
                        ${stepTime ? `<div class="text-xs text-gray-500 mt-1">${stepTime}</div>` : ''}
                    </div>
                `;
            });
            
            timelineHTML += `
                    </div>
                </div>
            `;
            
            // Build status badge
            const statusColors = {
                'pending': 'pending',
                'approved': 'processing',
                'processing': 'processing',
                'completed': 'completed',
                'cancelled': 'cancelled'
            };
            
            const statusBadgeClass = statusColors[replacement.status] || 'pending';
            
            // Build content HTML with beautiful layout
            content.innerHTML = `
                <div>
                    <!-- Header with gradient -->
                    <div class="info-card">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Replacement Tracking</h3>
                                <p class="text-sm opacity-90">Track your replacement request in real-time</p>
                            </div>
                            <span class="status-badge ${statusBadgeClass}">
                                ${replacement.status.charAt(0).toUpperCase() + replacement.status.slice(1)}
                            </span>
                        </div>
                        <div class="mt-4">
                            <div class="text-sm opacity-80">Request ID: <span class="font-bold">REP-${replacement.id}</span></div>
                        </div>
                    </div>
                    
                    <!-- Horizontal Timeline -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">REPLACEMENT PROCESS</h4>
                        ${timelineHTML}
                    </div>
                    
                    <!-- Current Status Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 mb-6 border border-blue-100">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 mb-1">Current Status</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    ${steps[currentStepIndex]?.description || 'Processing your request...'}
                                </p>
                                <div class="flex items-center text-sm">
                                    <i class="far fa-clock text-gray-400 mr-2"></i>
                                    <span class="text-gray-600">
                                        Last updated: ${new Date(replacement.updated_at).toLocaleString('en-US', {
                                            month: 'short',
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Details Grid -->
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Product ID</span>
                            <span class="detail-value">PROD-${replacement.product_id}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Order ID</span>
                            <span class="detail-value">ORD-${replacement.order_id}</span>
                        </div>
                        ${replacement.tracking_number ? `
                            <div class="detail-item">
                                <span class="detail-label">Tracking Number</span>
                                <span class="detail-value">${replacement.tracking_number}</span>
                            </div>
                        ` : ''}
                        ${replacement.estimated_delivery_date ? `
                            <div class="detail-item">
                                <span class="detail-label">Estimated Delivery</span>
                                <span class="detail-value">
                                    ${new Date(replacement.estimated_delivery_date).toLocaleDateString('en-US', {
                                        weekday: 'short',
                                        month: 'short',
                                        day: 'numeric',
                                        year: 'numeric'
                                    })}
                                </span>
                            </div>
                        ` : ''}
                        ${replacement.reason ? `
                            <div class="detail-item">
                                <span class="detail-label">Reason</span>
                                <span class="detail-value">${replacement.reason.replace('_', ' ').toUpperCase()}</span>
                            </div>
                        ` : ''}
                    </div>
                    
                    <!-- Action Required Section -->
                    ${replacement.current_step === 'shipped_to_vendor' ? `
                        <div class="action-card">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <i class="fas fa-exclamation-circle text-yellow-600 text-xl mt-1"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-yellow-800 mb-2">Action Required</h4>
                                    <p class="text-sm text-yellow-700 mb-4">
                                        Please ship the original item to the vendor address provided in your email confirmation. 
                                        Once shipped, mark it as shipped below.
                                    </p>
                                    <div class="flex space-x-3">
                                        <button onclick="markAsShipped(${replacement.id})" 
                                                class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Mark as Shipped
                                        </button>
                                        <button onclick="downloadShippingLabel(${replacement.id})" 
                                                class="px-6 py-3 bg-white border border-yellow-300 text-yellow-700 font-semibold rounded-lg hover:bg-yellow-50 transition-colors">
                                            <i class="fas fa-download mr-2"></i>
                                            Download Label
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                    
                    <!-- Estimated Timeline -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 mb-6 border border-green-100">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Estimated Timeline
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Request Review</span>
                                <span class="text-sm font-semibold text-green-600">1-2 Business Days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Shipping & Verification</span>
                                <span class="text-sm font-semibold text-green-600">3-5 Business Days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Replacement Delivery</span>
                                <span class="text-sm font-semibold text-green-600">5-7 Business Days</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="invisible text-sm text-gray-500">
                            <i class="far fa-question-circle mr-1"></i>
                            Need to cancel this request? 
                            <button onclick="cancelReplacement(${replacement.id})" class="text-red-600 hover:text-red-800 font-medium ml-1">
                                Click here
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="closeReplacementTrackingModal()" 
                                    class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                                Close
                            </button>
                            <button onclick="printTracking(${replacement.id})" 
                                    class="px-6 py-2.5 bg-white border border-blue-300 text-blue-700 font-semibold rounded-lg hover:bg-blue-50 transition-colors flex items-center">
                                <i class="fas fa-print mr-2"></i>
                                Print Details
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Show Return Tracking
        function showReturnTracking(returnId) {
            const modal = document.getElementById('returnTrackingModal');
            const content = document.getElementById('returnTrackingContent');
            
            // Show loading
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-orange-500 mb-4"></i>
                    <p class="text-gray-600">Loading return request information...</p>
                </div>
            `;
            
            modal.classList.remove('hidden');
            
            // Fetch return tracking data
            fetch(`/returns/track/${returnId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderReturnTracking(data.returnRequest, data.trackingSteps);
                    } else {
                        content.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                                <p class="text-gray-600">Error loading return information</p>
                                <p class="text-sm text-gray-500">${data.message}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                            <p class="text-gray-600">Network error. Please try again.</p>
                        </div>
                    `;
                });
        }

        // Render Return Tracking
        function renderReturnTracking(returnRequest, trackingSteps) {
            const content = document.getElementById('returnTrackingContent');
            
            // Define return process steps
            const steps = [
                {
                    id: 'request_submitted',
                    label: 'Request Submitted',
                    icon: 'fa-paper-plane',
                    description: 'Your return request has been submitted',
                    color: 'orange'
                },
                {
                    id: 'request_approved',
                    label: 'Approved',
                    icon: 'fa-check-circle',
                    description: 'Request approved by vendor',
                    color: 'green'
                },
                {
                    id: 'pickup_scheduled',
                    label: 'Pickup Scheduled',
                    icon: 'fa-calendar-alt',
                    description: 'Pickup scheduled for your item',
                    color: 'yellow'
                },
                {
                    id: 'item_picked_up',
                    label: 'Item Picked Up',
                    icon: 'fa-truck-pickup',
                    description: 'Item has been picked up',
                    color: 'blue'
                },
                {
                    id: 'item_received',
                    label: 'Item Received',
                    icon: 'fa-box-open',
                    description: 'Vendor received your return',
                    color: 'purple'
                },
                {
                    id: 'quality_check',
                    label: 'Quality Check',
                    icon: 'fa-search',
                    description: 'Item being inspected',
                    color: 'indigo'
                },
                {
                    id: 'refund_processing',
                    label: 'Refund Processing',
                    icon: 'fa-credit-card',
                    description: 'Refund being processed',
                    color: 'pink'
                },
                {
                    id: 'refund_completed',
                    label: 'Refund Completed',
                    icon: 'fa-check-double',
                    description: 'Refund completed successfully',
                    color: 'green'
                }
            ];
            
            // Find current step index
            const currentStepIndex = steps.findIndex(step => step.id === returnRequest.current_step);
            
            // Build horizontal timeline HTML
            let timelineHTML = `
                <div class="horizontal-timeline">
                    <div class="flex justify-between relative">
            `;
            
            steps.forEach((step, index) => {
                const isCompleted = index <= currentStepIndex;
                const isCurrent = index === currentStepIndex;
                const stepClass = isCurrent ? 'current' : (isCompleted ? 'completed' : '');
                const stepTime = trackingSteps[step.id] 
                    ? new Date(trackingSteps[step.id]).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
                    : '';
                
                timelineHTML += `
                    <div class="timeline-step ${stepClass}">
                        <div class="step-icon">
                            <i class="fas ${step.icon}"></i>
                        </div>
                        ${index < steps.length - 1 ? '<div class="timeline-connector"></div>' : ''}
                        <div class="step-label">${step.label}</div>
                        ${stepTime ? `<div class="text-xs text-gray-500 mt-1">${stepTime}</div>` : ''}
                    </div>
                `;
            });
            
            timelineHTML += `
                    </div>
                </div>
            `;
            
            // Build status badge
            const statusColors = {
                'pending': 'pending',
                'approved': 'processing',
                'processing': 'processing',
                'completed': 'completed',
                'cancelled': 'cancelled',
                'return_requested': 'return-requested'
            };
            
            const statusBadgeClass = statusColors[returnRequest.status] || 'pending';
            
            // Build content HTML with beautiful layout
            content.innerHTML = `
                <div>
                    <!-- Header with gradient -->
                    <div class="info-card return">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Return Request Tracking</h3>
                                <p class="text-sm opacity-90">Track your return request in real-time</p>
                            </div>
                            <span class="status-badge ${statusBadgeClass}">
                                ${returnRequest.status.charAt(0).toUpperCase() + returnRequest.status.slice(1).replace('_', ' ')}
                            </span>
                        </div>
                        <div class="mt-4">
                            <div class="text-sm opacity-80">Request ID: <span class="font-bold">RET-${returnRequest.id}</span></div>
                        </div>
                    </div>
                    
                    <!-- Horizontal Timeline -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">RETURN PROCESS</h4>
                        ${timelineHTML}
                    </div>
                    
                    <!-- Current Status Card -->
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-5 mb-6 border border-orange-100">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-info-circle text-orange-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 mb-1">Current Status</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    ${steps[currentStepIndex]?.description || 'Processing your request...'}
                                </p>
                                <div class="flex items-center text-sm">
                                    <i class="far fa-clock text-gray-400 mr-2"></i>
                                    <span class="text-gray-600">
                                        Last updated: ${new Date(returnRequest.updated_at).toLocaleString('en-US', {
                                            month: 'short',
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Details Grid -->
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Product ID</span>
                            <span class="detail-value">PROD-${returnRequest.product_id}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Order ID</span>
                            <span class="detail-value">ORD-${returnRequest.order_id}</span>
                        </div>
                        ${returnRequest.tracking_number ? `
                            <div class="detail-item">
                                <span class="detail-label">Tracking Number</span>
                                <span class="detail-value">${returnRequest.tracking_number}</span>
                            </div>
                        ` : ''}
                        ${returnRequest.refund_amount ? `
                            <div class="detail-item">
                                <span class="detail-label">Refund Amount</span>
                                <span class="detail-value">$${returnRequest.refund_amount}</span>
                            </div>
                        ` : ''}
                        ${returnRequest.reason ? `
                            <div class="detail-item">
                                <span class="detail-label">Reason</span>
                                <span class="detail-value">${returnRequest.reason.replace('_', ' ').toUpperCase()}</span>
                            </div>
                        ` : ''}
                        ${returnRequest.refund_method ? `
                            <div class="detail-item">
                                <span class="detail-label">Refund Method</span>
                                <span class="detail-value">${returnRequest.refund_method.toUpperCase()}</span>
                            </div>
                        ` : ''}
                    </div>
                    
                    <!-- Action Required Section -->
                    ${returnRequest.current_step === 'pickup_scheduled' ? `
                        <div class="action-card return">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <i class="fas fa-exclamation-circle text-orange-600 text-xl mt-1"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-orange-800 mb-2">Pickup Scheduled</h4>
                                    <p class="text-sm text-orange-700 mb-4">
                                        Pickup has been scheduled for: <strong>${returnRequest.pickup_date ? new Date(returnRequest.pickup_date).toLocaleDateString() : 'To be confirmed'}</strong>
                                    </p>
                                    <p class="text-sm text-orange-700 mb-4">
                                        Please keep the item ready for pickup at your address.
                                    </p>
                                    <div class="flex space-x-3">
                                        <button onclick="confirmPickupReady(${returnRequest.id})" 
                                                class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Confirm Item is Ready
                                        </button>
                                        <button onclick="reschedulePickup(${returnRequest.id})" 
                                                class="px-6 py-3 bg-white border border-orange-300 text-orange-700 font-semibold rounded-lg hover:bg-orange-50 transition-colors">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            Reschedule Pickup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${returnRequest.current_step === 'refund_processing' || returnRequest.current_step === 'refund_completed' ? `
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 mb-6 border border-green-100">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                                Refund Information
                            </h4>
                            <div class="space-y-3">
                                ${returnRequest.refund_amount ? `
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Refund Amount</span>
                                        <span class="text-sm font-semibold text-green-600">$${returnRequest.refund_amount}</span>
                                    </div>
                                ` : ''}
                                ${returnRequest.refund_method ? `
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Refund Method</span>
                                        <span class="text-sm font-semibold text-green-600">${returnRequest.refund_method.toUpperCase()}</span>
                                    </div>
                                ` : ''}
                                ${returnRequest.estimated_refund_date ? `
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Estimated Refund Date</span>
                                        <span class="text-sm font-semibold text-green-600">
                                            ${new Date(returnRequest.estimated_refund_date).toLocaleDateString('en-US', {
                                                month: 'short',
                                                day: 'numeric',
                                                year: 'numeric'
                                            })}
                                        </span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    ` : ''}
                    
                    <!-- Estimated Timeline -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 mb-6 border border-green-100">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Estimated Timeline
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Request Review</span>
                                <span class="text-sm font-semibold text-green-600">1-2 Business Days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pickup & Shipping</span>
                                <span class="text-sm font-semibold text-green-600">2-3 Business Days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Quality Check</span>
                                <span class="text-sm font-semibold text-green-600">2-3 Business Days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Refund Processing</span>
                                <span class="text-sm font-semibold text-green-600">3-5 Business Days</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="invisible text-sm text-gray-500">
                            <i class="far fa-question-circle mr-1"></i>
                            Need to cancel this request? 
                            <button onclick="cancelReturn(${returnRequest.id})" class="text-red-600 hover:text-red-800 font-medium ml-1">
                                Click here
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="closeReturnTrackingModal()" 
                                    class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                                Close
                            </button>
                            <button onclick="printReturnDetails(${returnRequest.id})" 
                                    class="px-6 py-2.5 bg-white border border-orange-300 text-orange-700 font-semibold rounded-lg hover:bg-orange-50 transition-colors flex items-center">
                                <i class="fas fa-print mr-2"></i>
                                Print Details
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Helper functions for return tracking
        function confirmPickupReady(returnId) {
            if (confirm('Confirm that the item is ready for pickup?')) {
                const data = {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                fetch(`/confirm-pickup-ready/${returnId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Thank you! The pickup team will collect your item.');
                        closeReturnTrackingModal();
                    } else {
                        alert(result.message || 'Error confirming pickup');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please try again.');
                });
            }
        }

        function reschedulePickup(returnId) {
            alert('Redirecting to pickup rescheduling page...');
            // In production, this would open a modal or redirect to a rescheduling page
        }

        function cancelReturn(returnId) {
            if (confirm('Are you sure you want to cancel this return request?')) {
                const data = {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                fetch(`/cancel-return/${returnId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Return request cancelled successfully!');
                        closeReturnTrackingModal();
                        location.reload();
                    } else {
                        alert(result.message || 'Error cancelling request');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please try again.');
                });
            }
        }

        function printReturnDetails(returnId) {
            window.print();
        }

        // Helper functions for replacement tracking
        function downloadShippingLabel(replacementId) {
            alert('Downloading shipping label...');
            // In production, this would generate and download a PDF label
        }

        function cancelReplacement(replacementId) {
            if (confirm('Are you sure you want to cancel this replacement request?')) {
                const data = {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                fetch(`/cancel-replacement/${replacementId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Replacement request cancelled successfully!');
                        closeReplacementTrackingModal();
                        location.reload();
                    } else {
                        alert(result.message || 'Error cancelling request');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please try again.');
                });
            }
        }

        function printTracking(replacementId) {
            window.print();
        }

        // Mark as Shipped Function
        function markAsShipped(replacementId) {
            if (confirm('Have you shipped the original item to the vendor?')) {
                const data = {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                fetch(`/mark-replacement-shipped/${replacementId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Thank you! The vendor will update the status once they receive the item.');
                        closeReplacementTrackingModal();
                        // Refresh the page to update button status
                        location.reload();
                    } else {
                        alert(result.message || 'Error updating status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please try again.');
                });
            }
        }

        // Close Replacement Tracking Modal
        function closeReplacementTrackingModal() {
            document.getElementById('replacementTrackingModal').classList.add('hidden');
        }

        // Close Return Tracking Modal
        function closeReturnTrackingModal() {
            document.getElementById('returnTrackingModal').classList.add('hidden');
        }

        // Update Submit Replace Request function
        function submitReplaceRequest(productId, orderId) {
            const reason = document.getElementById('replaceReason').value;
            const details = document.getElementById('replaceDetails').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;
            
            if (!reason) {
                alert('Please select a reason for replacement');
                return;
            }
            
            if (!agreeTerms) {
                alert('Please agree to the terms and conditions');
                return;
            }
            
            // Prepare data for AJAX request
            const data = {
                product_id: productId,
                order_id: orderId,
                reason: reason,
                details: details,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            
            // Show loading
            const submitBtn = document.querySelector('#replaceContent button[onclick^="submitReplaceRequest"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;
            
            fetch('/submit-replace-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Replacement request submitted successfully! You can track the replacement process.');
                    closeReplaceModal();
                    // Refresh page to show new tracking button
                    location.reload();
                } else {
                    alert(result.message || 'Error submitting request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }

        // Update modal closing events
        document.addEventListener('click', function(event) {
            const replacementModal = document.getElementById('replacementTrackingModal');
            const returnModal = document.getElementById('returnTrackingModal');
            const rateModal = document.getElementById('rateProductModal');
            const replaceModal = document.getElementById('replaceProductModal');
            const returnOptionsModal = document.getElementById('returnProductModal');
            
            if (event.target === replacementModal) closeReplacementTrackingModal();
            if (event.target === returnModal) closeReturnTrackingModal();
            if (event.target === rateModal) closeRateModal();
            if (event.target === replaceModal) closeReplaceModal();
            if (event.target === returnOptionsModal) closeReturnModal();
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeReplacementTrackingModal();
                closeReturnTrackingModal();
                closeRateModal();
                closeReplaceModal();
                closeReturnModal();
            }
        });

        // Tab switching
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            document.querySelector('header button').addEventListener('click', function() {
                alert('Mobile sidebar would open here');
            });

            // Tab switching
            document.querySelectorAll('.nav-tabs a').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.nav-tabs a').forEach(t => {
                        t.classList.remove('tab-active', 'border-blue-600', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });
                    this.classList.add('tab-active', 'border-blue-600', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');
                    
                    // Filter orders based on tab
                    const filter = this.textContent.toLowerCase().split(' ')[0];
                    document.querySelectorAll('.order-card').forEach(card => {
                        if (filter === 'all') {
                            card.style.display = 'block';
                        } else {
                            const hasClass = card.classList.contains(`order-status-${filter}`);
                            card.style.display = hasClass ? 'block' : 'none';
                        }
                    });
                });
            });

            // Search functionality
            document.querySelector('input[placeholder="Search orders..."]').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.order-card').forEach(card => {
                    const text = card.textContent.toLowerCase();
                    card.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });

            // Toggle order details
            document.querySelectorAll('.order-card button:has(.fa-chevron-down)').forEach(button => {
                button.addEventListener('click', function() {
                    const details = this.closest('.order-card').querySelector('#order-carts');
                    const isHidden = details.classList.contains('hidden');
                    
                    // Rotate icon
                    this.innerHTML = isHidden ? 
                        '<i class="fas fa-chevron-up"></i>' : 
                        '<i class="fas fa-chevron-down"></i>';
                    
                    // Toggle details
                    if (isHidden) {
                        details.classList.remove('hidden');
                    } else {
                        details.classList.add('hidden');
                    }
                });
            });

            // Track button
            document.querySelectorAll('.track-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const orderStatus = this.getAttribute('data-order-status');
                    
                    showTrackingModal(orderId, orderStatus);
                });
            });
        });

        // Function to show tracking modal with dynamic status
        function showTrackingModal(orderId, orderStatus) {
    const modal = document.getElementById('trackingModal');
    
    document.querySelector('#trackingModal h2').textContent = `Track Order ${orderId}`;
    
    const statusSteps = ['Order Placed', 'Processing', 'Shipped', 'Delivered'];
    const icons = ['fa-shopping-cart', 'fa-cog', 'fa-truck', 'fa-check'];
    const colorMap = {
        'Order Placed': 'bg-blue-500',
        'Processing':   'bg-yellow-500',
        'Shipped':      'bg-purple-500',
        'Delivered':    'bg-green-500',
    };

const matchedStatus = statusSteps.find(s => s.toLowerCase() === orderStatus.toLowerCase()) || 'Order Placed';
let activeIndex = statusSteps.indexOf(matchedStatus);
if (activeIndex === -1) activeIndex = 0;

const color = colorMap[matchedStatus] || 'bg-blue-500';

    // Grab the 4 step containers inside the flex row
    const stepDivs = document.querySelectorAll('#trackingModal .flex.items-center.justify-between > .text-center');
    const lines    = document.querySelectorAll('#trackingModal .flex.items-center.justify-between > .h-1');

    stepDivs.forEach((stepEl, i) => {
        const dot  = stepEl.querySelector('div');   // the circle
        const label = stepEl.querySelector('p');    // the label text

        if (i <= activeIndex) {
            dot.className = `w-10 h-10 ${color} rounded-full flex items-center justify-center mx-auto mb-2`;
            dot.innerHTML = `<i class="fas ${icons[i]} text-white"></i>`;
            label.className = 'text-sm font-medium text-blue-600';
        } else {
            dot.className = 'w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-2';
            dot.innerHTML = `<i class="fas ${icons[i]} text-gray-500"></i>`;
            label.className = 'text-sm font-medium text-gray-600';
        }
    });

    lines.forEach((line, i) => {
        line.className = i < activeIndex
            ? `h-1 flex-1 ${color}`
            : 'h-1 flex-1 bg-gray-300';
    });

    // Status message
    const statusMessages = {
        'Order Placed': ['Your order has been confirmed.',          'The seller has received your order.'],
        'Processing':   ['Your order is being prepared.',           'Items are being packed for shipping.'],
        'Shipped':      ['Your order is on its way.',               'The package is in transit with the carrier.'],
        'Delivered':    ['Your order has been delivered.',          'The package was delivered to your address.'],
    };
    const [msg, detail] = statusMessages[matchedStatus] || ['Processing your order.', ''];
    document.getElementById('statusMessage').textContent = msg;
    document.getElementById('statusDetails').textContent = detail;

    // Estimated delivery
    const daysMap = { 'Order Placed': 7, 'Processing': 5, 'Shipped': 3, 'Delivered': 0 };
    const deliveryDate = new Date();
    deliveryDate.setDate(deliveryDate.getDate() + (daysMap[orderStatus] ?? 7));
    document.getElementById('estimatedDelivery').textContent =
        deliveryDate.setDate(deliveryDate.getDate() + (daysMap[matchedStatus] ?? 7));

    modal.classList.remove('hidden');
}

        // Helper function to get color based on status
        function getStatusColorClass(status) {
            switch(status.toLowerCase()) {
                case 'order placed': return 'bg-blue-500';
                case 'processing': return 'bg-yellow-500';
                case 'shipped': return 'bg-purple-500';
                case 'delivered': return 'bg-green-500';
                default: return 'bg-blue-500';
            }
        }

        function closeTrackingModal() {
            document.getElementById('trackingModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('trackingModal');
            if (event.target === modal) {
                closeTrackingModal();
            }
        });

        // Keyboard support for closing modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeTrackingModal();
            }
        });
    </script>
</body>
</html>