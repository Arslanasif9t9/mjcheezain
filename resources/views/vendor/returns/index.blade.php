<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/page-loader.js') }}?v={{ @filemtime(public_path('js/page-loader.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Return Requests | Vendor Dashboard</title>
    
    <!-- Meta for CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ @filemtime(public_path('css/tailwind.css')) ?: 1 }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        table {font-size: 75.5%}
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .badge {
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .badge:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }
        
        .status-dropdown {
            position: fixed;
            z-index: 9999;
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 420px;
            max-width: 90vw;
            display: none;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.95);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }
        
        .status-dropdown.open {
            display: block;
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        
        .status-dropdown-header {
            padding: 24px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .status-dropdown-body {
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .status-option {
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 10px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid transparent;
        }
        
        .status-option:hover {
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }
        
        .status-option.selected {
            background-color: #fffbeb;
            border-color: #f59e0b;
        }
        
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
            backdrop-filter: blur(4px);
        }
        
        .modal-overlay.open {
            display: block;
        }
        
        body.modal-open {
            overflow: hidden;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .return-row {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .filter-btn {
            transition: all 0.3s ease;
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
        }
        
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #e5e7eb;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
            transition: width 0.5s ease;
        }
        
        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .step-badge.active {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
        }
        
        .step-badge.completed {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .stat-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        .table-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .table-header th {
            padding: 16px 24px;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
        }
        
        .table-row {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s;
        }
        
        .table-row:hover {
            background-color: #f9fafb;
        }
        
        .table-cell {
            padding: 18px 8px;
            vertical-align: middle;
        }
        
        .action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Timeline Styles */
        .timeline-item {
            position: relative;
            padding-left: 30px;
            margin-bottom: 20px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #f59e0b;
        }
        
        .timeline-item:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 12px;
            width: 2px;
            height: calc(100% + 8px);
            background: #d1d5db;
        }
        
        .timeline-item:last-child:after {
            display: none;
        }
        
        /* Status Colors */
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #d1fae5; color: #065f46; }
        .status-processing { background-color: #f3e8ff; color: #6b21a8; }
        .status-refunded { background-color: #fce7f3; color: #C94A72; }
        .status-completed { background-color: #f0fdf4; color: #166534; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        
        /* Refund Method Badges */
        .refund-method {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .refund-original { background-color: #f3e8ff; color: #6b21a8; }
        .refund-wallet { background-color: #fef3c7; color: #92400e; }
        .refund-credit { background-color: #fce7f3; color: #C94A72; }
        
        /* Image Gallery */
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
    <style>
        table {
            font-size: 75.5%;
        }
    </style>
    <style>
        /* ===== App-style retheme (mobile-first, matches customer panel) ===== */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .status-dropdown { border-radius: 1.25rem !important; }
        .status-dropdown-header {
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%) !important;
        }
        @media (max-width: 767px) {
            .status-dropdown {
                width: calc(100vw - 2rem);
                max-width: calc(100vw - 2rem);
            }
        }
    </style>
</head>

<body class="min-h-screen" style="background-color: #FFF6F0;">
    <div class="flex min-h-screen">
        <!-- Sidebar Component -->
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? auth()->user()->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            :user="$user"
            page='Returns'
        />

        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0">
        <x-vendor.app-header title="Return Orders" subtitle="{{ $stats['total'] }} requests · {{ $stats['pending'] }} pending" />
        <main class="flex-1 p-4 sm:p-6 pb-28 md:pb-8 overflow-y-auto page-enter">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center mb-5 md:mb-8 gap-4">
                <div class="hidden md:block">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 tracking-tight">Return Requests</h1>
                    <p class="text-sm text-gray-550 mt-1">Manage and track all return requests for your products</p>
                </div>
                
                <div class="relative w-full lg:w-1/3">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-400"></i>
                    <input id="searchInput" type="text" placeholder="Search returns..." 
                           class="border border-gray-300 pl-11 pr-12 py-2.5 rounded-xl w-full focus:outline-none focus:ring-2 focus:ring-orange-500/25 focus:border-orange-550 bg-white shadow-sm text-sm">
                    <button class="absolute right-2 top-1.5 bg-orange-600 text-white px-3 py-1.5 rounded-lg hover:bg-orange-700 transition text-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-orange-500 shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Total Requests</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-undo text-orange-600 text-sm"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-yellow-500 shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Pending</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-yellow-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-650 text-sm"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-green-500 shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Approved</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['approved'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-650 text-sm"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-[#E85D85] shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Processing</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['processing'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-pink-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-cog text-[#E85D85] text-sm"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-teal-500 shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Refunded</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['refunded'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-teal-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-teal-650 text-sm"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-red-500 shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Rejected</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['rejected'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-650 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Buttons (horizontally scrollable pills on mobile) -->
            <div class="flex md:flex-wrap gap-2 md:gap-3 mb-6 text-sm overflow-x-auto no-scrollbar -mx-4 px-4 md:mx-0 md:px-0 py-1">
                <button onclick="filterReturns('all')" 
                        class="filter-btn active whitespace-nowrap flex-shrink-0 px-4 md:px-5 py-2 md:py-2.5 bg-white border border-gray-300 rounded-full font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-list"></i> All Requests
                </button>
                <button onclick="filterReturns('pending')" 
                        class="filter-btn whitespace-nowrap flex-shrink-0 px-4 md:px-5 py-2 md:py-2.5 bg-white border border-gray-300 rounded-full font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-clock text-yellow-500"></i> Pending
                </button>
                <button onclick="filterReturns('approved')" 
                        class="filter-btn whitespace-nowrap flex-shrink-0 px-4 md:px-5 py-2 md:py-2.5 bg-white border border-gray-300 rounded-full font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i> Approved
                </button>
                <button onclick="filterReturns('processing')" 
                        class="filter-btn whitespace-nowrap flex-shrink-0 px-4 md:px-5 py-2 md:py-2.5 bg-white border border-gray-300 rounded-full font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-cog text-[#E85D85]"></i> Processing
                </button>
                <button onclick="filterReturns('refunded')" 
                        class="filter-btn whitespace-nowrap flex-shrink-0 px-4 md:px-5 py-2 md:py-2.5 bg-white border border-gray-300 rounded-full font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-teal-500"></i> Refunded
                </button>
                <button onclick="filterReturns('completed')" 
                        class="filter-btn whitespace-nowrap flex-shrink-0 px-4 md:px-5 py-2 md:py-2.5 bg-white border border-gray-300 rounded-full font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-check-double text-green-600"></i> Completed
                </button>
                <button onclick="filterReturns('rejected')" 
                        class="filter-btn whitespace-nowrap flex-shrink-0 px-4 md:px-5 py-2 md:py-2.5 bg-white border border-gray-300 rounded-full font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-times-circle text-red-500"></i> Rejected
                </button>
            </div>

            <!-- Mobile return cards (rendered by JS from the table rows below) -->
            <div id="returnsMobileList" class="md:hidden space-y-3"></div>
            @if($returns->hasPages())
            <div class="md:hidden mt-4">
                {{ $returns->links() }}
            </div>
            @endif

            <!-- Returns Table (desktop) -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hidden md:block">
                <div class="overflow-x-auto w-full">
                    <table class="min-w-[1100px] w-full text-left">
                        <thead class="table-header">
                            <tr>
                                <th class="text-left">Return ID</th>
                                <th class="text-left">Customer</th>
                                <th class="text-left">Product</th>
                                <th class="text-left">Reason</th>
                                <th class="text-left">Order Date</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Request Date</th>
                                <th class="text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white" id="returnsTableBody">
                            @forelse($returns as $return)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500',
                                        'vendor_reviewed' => 'bg-blue-500',
                                        'approved' => 'bg-green-500',
                                        'processing' => 'bg-pink-500',
                                        'refunded' => 'bg-teal-500',
                                        'completed' => 'bg-green-600',
                                        'rejected' => 'bg-red-500'
                                    ];
                                    
                                    $statusColor = $statusColors[$return->status] ?? 'bg-gray-500';
                                @endphp
                                
                                <tr class="table-row return-row" data-return-id="{{ $return->id }}">
                                    <td class="table-cell font-bold text-orange-600">
                                        {{-- RET-{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }} --}}
                                        RET-{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}-{{ \Carbon\Carbon::parse($return->updated_at)->format('y') }}
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="flex flex-col">
                                            <span class="font-medium">
                                                {{ $return->customer_first_name ?? 'Customer' }} {{ $return->customer_last_name ?? '' }}
                                            </span>
                                            {{-- <span class="text-xs text-gray-500">{{ $return->customer_email }}</span> --}}
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $productImage = DB::table('vendor_product_images')
                                                    ->where('product_id', $return->product_id)
                                                    ->where('is_primary', 1)
                                                    ->first();
                                            @endphp
                                            @if($productImage)
                                                <img src="{{ asset('storage/vendor/products/images/' . $productImage->image_path) }}" 
                                                     class="w-10 h-10 rounded-lg object-cover border border-gray-200"
                                                     alt="{{ $return->product_name }}">
                                            @else
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="font-medium text-gray-800">{{ Str::limit($return->product_name, 30) }}</span>
                                                <div class="text-xs text-gray-500">Order: ORD-{{ $return->order_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell">
                                        <span class="text-sm capitalize">{{ str_replace('_', ' ', $return->reason) }}</span>
                                        @if($return->details)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($return->details, 50) }}</div>
                                        @endif
                                    </td>
                                    
                                    <td class="table-cell text-gray-600">
                                        {{ \Carbon\Carbon::parse($return->order_date)->format('M d, Y') }}
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="relative inline-block">
                                            <span class="badge {{ $statusColor }} text-white cursor-pointer status-badge"
                                                  data-return-id="{{ $return->id }}"
                                                  data-current-status="{{ $return->status }}"
                                                  onclick="openStatusModal('{{ $return->id }}', '{{ $return->status }}')">
                                                {{ ucfirst($return->status) }}
                                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell text-gray-600">
                                        {{ \Carbon\Carbon::parse($return->created_at)->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($return->created_at)->format('h:i A') }}
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="flex gap-2">
                                            <button onclick="viewReturn({{ $return->id }})"
                                                    class="action-btn bg-orange-100 text-orange-700 hover:bg-orange-200">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            
                                            <button onclick="openStatusModal('{{ $return->id }}', '{{ $return->status }}')"
                                                    class="action-btn bg-green-100 text-green-700 hover:bg-green-200">
                                                <i class="fas fa-comment"></i> Comment
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-undo text-gray-400 text-2xl"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-600 mb-2">No return requests found</p>
                                            <p class="text-sm text-gray-500">When customers request returns, they'll appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($returns->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $returns->links() }}
                </div>
                @endif
            </div>
        </main>
        </div>
    </div>

    <!-- Status Modal -->
    <div id="statusModalOverlay" class="modal-overlay"></div>
    
    <div id="statusModal" class="status-dropdown">
        <button class="close-btn" onclick="closeStatusModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="status-dropdown-header">
            <h3 class="text-xl font-bold mb-1">Comment on Return</h3>
            <p class="opacity-90">Request: <span id="modalRequestId" class="font-semibold"></span></p>
        </div>

        <div class="status-dropdown-body">
            <!-- Current Status Info -->
            <div id="currentStatusInfo" class="mb-6 p-4 bg-orange-50 rounded-lg">
                <h4 class="font-semibold text-orange-800 mb-2">Current Status</h4>
                <div class="flex items-center justify-between">
                    <span id="currentStatusText" class="font-medium"></span>
                    <span id="currentStatusBadge" class="badge"></span>
                </div>
            </div>

            <!-- Vendor Comment -->
            <div class="mb-6 app-card p-4" style="border:1px solid rgba(232,93,133,0.12);">
                <h4 class="font-semibold text-gray-800 mb-1 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs" style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                        <i class="fas fa-comment-dots"></i>
                    </span>
                    Your Comment
                </h4>
                <p class="text-xs text-gray-500 mb-3">
                    Vendors can only share an opinion on the request (e.g. "this is not our fault,
                    customer opened the seal" or "yes, we sent the wrong item"). MJ Cheezain Admin
                    reviews your comment together with the customer's request and makes the final
                    approve/reject decision.
                </p>

                <!-- Quick-select reason chips: purely to speed up typing, they just prefill/append
                     text into the comment box below — the free-text comment is still what's saved. -->
                <div class="mb-3">
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide mb-2">Quick reasons (optional)</p>
                    <div class="flex flex-wrap gap-2" id="reasonChips">
                        <button type="button" class="reason-chip px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                style="border-color:#E85D85;color:#E85D85;background:#fff;" data-text="This is not our fault.">
                            <i class="fas fa-shield-halved mr-1"></i> Not our fault
                        </button>
                        <button type="button" class="reason-chip px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                style="border-color:#E85D85;color:#E85D85;background:#fff;" data-text="Item was fine and checked when it was shipped.">
                            <i class="fas fa-box-check mr-1"></i> Item was fine when shipped
                        </button>
                        <button type="button" class="reason-chip px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                style="border-color:#E85D85;color:#E85D85;background:#fff;" data-text="This looks like the customer changed their mind, not a product issue.">
                            <i class="fas fa-user-clock mr-1"></i> Customer changed mind
                        </button>
                        <button type="button" class="reason-chip px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                style="border-color:#E85D85;color:#E85D85;background:#fff;" data-text="Yes, we agree there was an issue with this item.">
                            <i class="fas fa-check mr-1"></i> We agree, issue confirmed
                        </button>
                    </div>
                </div>

                <textarea id="vendorCommentInput" rows="4" maxlength="1000"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-2 focus:outline-none transition"
                          style="--tw-ring-color:#FFC1D3;"
                          placeholder="Add your comment about this return request..."></textarea>
                <p class="text-right text-[11px] text-gray-400 mt-1"><span id="vendorCommentCount">0</span>/1000</p>
            </div>

            <!-- Additional Options -->
            {{-- <div id="additionalOptions" class="mb-6">
                <h4 class="font-semibold text-gray-700 mb-3">Additional Information</h4>
                
                <!-- Step Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Step</label>
                    <select id="stepSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Select Step</option>
                        <option value="request_submitted">Request Submitted</option>
                        <option value="under_review">Under Review</option>
                        <option value="approved">Approved</option>
                        <option value="pickup_scheduled">Pickup Scheduled</option>
                        <option value="pickup_completed">Pickup Completed</option>
                        <option value="item_received">Item Received</option>
                        <option value="quality_check">Quality Check</option>
                        <option value="refund_processing">Refund Processing</option>
                        <option value="refunded">Refunded</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <!-- Tracking Number -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <input type="text" id="trackingNumber" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="Enter tracking number">
                </div>
                
                <!-- Pickup Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Date</label>
                    <input type="date" id="pickupDate" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                
                <!-- Refund Method -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Refund Method</label>
                    <select id="refundMethod" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Select Method</option>
                        <option value="original_payment">Original Payment Method</option>
                        <option value="wallet">Wallet</option>
                        <option value="store_credit">Store Credit</option>
                    </select>
                </div>
                
                <!-- Notes -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="statusNotes" 
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                              rows="3" 
                              placeholder="Add any notes about this status update..."></textarea>
                </div>
            </div> --}}
            
            <!-- Action Buttons -->
            <div class="pt-4 border-t border-gray-200">
                <button id="updateStatusBtn" onclick="updateReturnStatus()"
                        class="w-full py-3.5 brand-gradient brand-shadow text-white rounded-full font-semibold hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed border-0"
                        disabled>
                    Submit Comment
                </button>
                <button onclick="closeStatusModal()"
                        class="w-full py-3.5 bg-gray-100 text-gray-700 rounded-full font-semibold hover:bg-gray-200 transition mt-3 border-0">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Current return being edited
            let currentReturnId = null;
            let currentReturnStatus = null;
            let selectedStatus = null;
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#returnsTableBody tr');
            
            if (searchInput && tableRows.length > 0) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    
                    tableRows.forEach(row => {
                        if (row.querySelector('td')) {
                            const rowText = row.textContent.toLowerCase();
                            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
                        }
                    });
                });
            }
            
            // Close modal when clicking overlay
            document.getElementById('statusModalOverlay').addEventListener('click', closeStatusModal);
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeStatusModal();
                }
            });
        });
        
        // Filter returns by status
        async function filterReturns(status) {
            // Update active filter button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            const activeBtn = Array.from(document.querySelectorAll('.filter-btn')).find(btn => 
                btn.textContent.includes(status.charAt(0).toUpperCase() + status.slice(1)) || 
                (status === 'all' && btn.textContent.includes('All Requests'))
            );
            
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
            
            // Show loading
            const tableBody = document.getElementById('returnsTableBody');
            const originalContent = tableBody.innerHTML;
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 border-4 border-orange-200 border-t-orange-600 rounded-full spinner mb-4"></div>
                            <p class="text-gray-600">Loading returns...</p>
                        </div>
                    </td>
                </tr>
            `;
            
            try {
                const response = await fetch(`/vendor/returns/filter/${status}`);
                const html = await response.text();
                tableBody.innerHTML = html;
            } catch (error) {
                console.error('Error filtering:', error);
                tableBody.innerHTML = originalContent;
                showNotification('Error loading returns', 'error');
            } finally {
                // Keep the mobile card list in sync with the (possibly new) table rows
                if (typeof renderMobileReturnCards === 'function') renderMobileReturnCards();
            }
        }
        
        // View return details
        function viewReturn(returnId) {
            window.open(`/vendor/returns/${returnId}`, '_blank');
        }
        
        // Open status modal
        function openStatusModal(returnId, currentStatus) {
            currentReturnId = returnId;
            currentReturnStatus = currentStatus;
            selectedStatus = null;
            
            // Set return ID in modal
            document.getElementById('modalRequestId').textContent = `RET-${String(returnId).padStart(6, '0')}`;
            
            // Set current status info
            document.getElementById('currentStatusText').textContent = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
            document.getElementById('currentStatusBadge').innerHTML = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
            document.getElementById('currentStatusBadge').className = `badge ${getStatusColor(currentStatus)} text-white`;
            
            // Reset the comment box and quick-reason chips
            const commentInput = document.getElementById('vendorCommentInput');
            if (commentInput) commentInput.value = '';
            const commentCounter = document.getElementById('vendorCommentCount');
            if (commentCounter) commentCounter.textContent = '0';
            document.querySelectorAll('.reason-chip').forEach(function (chip) {
                chip.classList.remove('is-active');
                chip.style.background = '#fff';
                chip.style.color = '#E85D85';
            });

            // Disable update button initially (enabled once a comment is typed)
            document.getElementById('updateStatusBtn').disabled = true;

            // Show modal
            document.getElementById('statusModalOverlay').classList.add('open');
            document.getElementById('statusModal').classList.add('open');
            document.body.classList.add('modal-open');
        }
        
        // Get status color class
        function getStatusColor(status) {
            const colors = {
                'pending': 'bg-yellow-500',
                'vendor_reviewed': 'bg-blue-500',
                'approved': 'bg-green-500',
                'processing': 'bg-pink-500',
                'refunded': 'bg-teal-500',
                'completed': 'bg-green-600',
                'rejected': 'bg-red-500'
            };
            return colors[status] || 'bg-gray-500';
        }
        
        // Close status modal
        function closeStatusModal() {
            document.getElementById('statusModalOverlay').classList.remove('open');
            document.getElementById('statusModal').classList.remove('open');
            document.body.classList.remove('modal-open');
            
            currentReturnId = null;
            currentReturnStatus = null;
            selectedStatus = null;
        }
        
        // Enable the submit button once the vendor types a comment, and keep the char counter live
        document.addEventListener('input', function(e) {
            if (e.target && e.target.id === 'vendorCommentInput') {
                document.getElementById('updateStatusBtn').disabled = e.target.value.trim().length === 0;
                const counter = document.getElementById('vendorCommentCount');
                if (counter) counter.textContent = e.target.value.length;
            }
        });

        // Quick reason chips: append (or toggle off) their text into the comment box.
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.reason-chip').forEach(function (chip) {
                chip.addEventListener('click', function () {
                    const textarea = document.getElementById('vendorCommentInput');
                    const phrase = chip.getAttribute('data-text');
                    const isActive = chip.classList.contains('is-active');

                    if (isActive) {
                        textarea.value = textarea.value.split(phrase).join('').replace(/\s{2,}/g, ' ').trim();
                        chip.classList.remove('is-active');
                        chip.style.background = '#fff';
                        chip.style.color = '#E85D85';
                    } else {
                        textarea.value = (textarea.value.trim() ? textarea.value.trim() + ' ' : '') + phrase;
                        chip.classList.add('is-active');
                        chip.style.background = 'linear-gradient(115deg,#FF7DA0,#FFC275)';
                        chip.style.color = '#fff';
                    }

                    textarea.dispatchEvent(new Event('input', { bubbles: true }));
                });
            });
        });

        // Submit the vendor's comment (opinion only — no status/decision change)
        async function updateReturnStatus() {
            const commentInput = document.getElementById('vendorCommentInput');
            const comment = commentInput ? commentInput.value.trim() : '';
            if (!currentReturnId || !comment) {
                return;
            }

            const updateBtn = document.getElementById('updateStatusBtn');

            // Show loading state
            const originalText = updateBtn.innerHTML;
            updateBtn.innerHTML = '<i class="fas fa-spinner spinner mr-2"></i> Submitting...';
            updateBtn.disabled = true;

            // Prepare data
            const data = {
                return_id: currentReturnId,
                comment: comment,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            try {
                const response = await fetch("{{ route('vendor.returns.update-status') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('Comment submitted — Admin will make the final decision.', 'success');

                    // Close modal after delay
                    setTimeout(() => {
                        closeStatusModal();
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Update failed');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to update status. Please try again.', 'error');
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            }
        }
        
        // Show notification
        function showNotification(message, type) {
            // Remove existing notifications
            document.querySelectorAll('.notification-toast').forEach(toast => toast.remove());
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification-toast fixed top-6 right-6 px-6 py-4 rounded-xl shadow-xl text-white font-medium z-[10000] transform transition-all duration-300 ${
                type === 'success' ? 'bg-gradient-to-r from-green-500 to-teal-500' : 'bg-gradient-to-r from-red-500 to-pink-500'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} text-xl"></i>
                    <span>${message}</span>
                </div>
            `;
            
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            // Remove after 4 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentElement) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }
    </script>

    <!-- Mobile app-card list: built from the (hidden) desktop table rows so filters/updates stay in sync -->
    <script>
        (function () {
            function esc(s) {
                const d = document.createElement('div');
                d.textContent = s == null ? '' : String(s);
                return d.innerHTML;
            }

            window.renderMobileReturnCards = function () {
                const list = document.getElementById('returnsMobileList');
                if (!list) return;

                const rows = document.querySelectorAll('#returnsTableBody tr[data-return-id]');
                if (!rows.length) {
                    list.innerHTML = `
                        <div class="app-card p-10 text-center">
                            <div class="w-16 h-16 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-3">
                                <i class="fas fa-undo text-2xl text-[#E85D85]"></i>
                            </div>
                            <p class="text-gray-600 text-sm font-semibold">No return requests found</p>
                            <p class="text-gray-400 text-xs mt-1">When customers request returns, they'll appear here.</p>
                        </div>`;
                    return;
                }

                let html = '';
                rows.forEach(row => {
                    const id = row.getAttribute('data-return-id');
                    const code = row.cells[0].textContent.trim();
                    const customer = row.cells[1].textContent.trim().replace(/\s+/g, ' ');
                    const img = row.cells[2].querySelector('img');
                    const name = (row.cells[2].querySelector('.font-medium')?.textContent || '').trim();
                    const orderLine = (row.cells[2].querySelector('.text-xs')?.textContent || '').trim();
                    const reason = (row.cells[3].querySelector('.text-sm')?.textContent || row.cells[3].textContent).trim();
                    const badgeEl = row.cells[5].querySelector('.status-badge');
                    const status = badgeEl ? (badgeEl.getAttribute('data-current-status') || 'pending') : 'pending';
                    const statusLabel = status.charAt(0).toUpperCase() + status.slice(1);
                    const reqDate = row.cells[6].textContent.trim().replace(/\s+/g, ' ');
                    const thumb = img
                        ? `<img src="${img.src}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0" alt="">`
                        : `<div class="w-12 h-12 rounded-xl brand-gradient-soft flex items-center justify-center flex-shrink-0"><i class="fas fa-box text-[#E85D85]"></i></div>`;

                    html += `
                    <div class="m-return-card app-card p-4">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-[11px] font-bold text-[#E85D85] tracking-wide truncate">${esc(code)}</span>
                            <span class="badge ${getStatusColor(status)} text-white cursor-pointer status-badge flex-shrink-0"
                                  data-return-id="${esc(id)}" data-current-status="${esc(status)}"
                                  onclick="openStatusModal('${esc(id)}', '${esc(status)}')">
                                ${esc(statusLabel)} <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            ${thumb}
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-gray-900 truncate">${esc(name)}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5 truncate">${esc(orderLine)}${orderLine && customer ? ' &middot; ' : ''}${esc(customer)}</p>
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="brand-gradient-soft rounded-xl px-3 py-2">
                                <p class="text-[10px] text-gray-500 font-medium">Reason</p>
                                <p class="text-[11px] font-semibold text-gray-800 capitalize truncate">${esc(reason)}</p>
                            </div>
                            <div class="brand-gradient-soft rounded-xl px-3 py-2">
                                <p class="text-[10px] text-gray-500 font-medium">Requested</p>
                                <p class="text-[11px] font-semibold text-gray-800 truncate">${esc(reqDate)}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-[#E85D85]/10 flex gap-2">
                            <button onclick="viewReturn(${parseInt(id, 10)})"
                                    class="flex-1 py-2.5 rounded-full text-white text-xs font-semibold brand-gradient brand-shadow border-0 active:scale-[0.98] transition">
                                <i class="fas fa-eye mr-1"></i> View
                            </button>
                            <button onclick="openStatusModal('${esc(id)}', '${esc(status)}')"
                                    class="flex-1 py-2.5 rounded-full text-xs font-semibold brand-gradient-soft text-[#E85D85] border border-[#E85D85]/20 active:scale-[0.98] transition">
                                <i class="fas fa-comment mr-1"></i> Comment
                            </button>
                        </div>
                    </div>`;
                });
                list.innerHTML = html;
            };

            document.addEventListener('DOMContentLoaded', function () {
                renderMobileReturnCards();

                // Extend the search box to filter the mobile cards as well
                const search = document.getElementById('searchInput');
                if (search) {
                    search.addEventListener('keyup', function () {
                        const term = this.value.toLowerCase();
                        document.querySelectorAll('#returnsMobileList .m-return-card').forEach(card => {
                            card.style.display = card.textContent.toLowerCase().includes(term) ? '' : 'none';
                        });
                    });
                }
            });
        })();
    </script>
    <x-vendor.mobile-nav />
</body>
</html>