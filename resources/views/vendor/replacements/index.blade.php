<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/page-loader.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Replacement Requests | Vendor Dashboard</title>
    
    <!-- Meta for CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
            width: 380px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .status-dropdown-body {
            padding: 20px;
            max-height: 60vh;
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
            background-color: #eff6ff;
            border-color: #3b82f6;
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
        
        .replacement-row {
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4);
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
            background: linear-gradient(90deg, #10b981, #34d399);
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
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: #3b82f6;
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
    </style>
    {{-- table scroll for width --}}
    <style>
        table {
            font-size: 75.5%;
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
            page='Replacements'
        />

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 pt-16 sm:pt-6 overflow-y-auto">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 tracking-tight">Replacement Requests</h1>
                    <p class="text-sm text-gray-550 mt-1">Manage and track all replacement requests for your products</p>
                </div>
                
                <div class="relative w-full lg:w-1/3">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-400"></i>
                    <input id="searchInput" type="text" placeholder="Search replacements..." 
                           class="border border-gray-300 pl-11 pr-12 py-2.5 rounded-xl w-full focus:outline-none focus:ring-2 focus:ring-pink-300/25 focus:border-[#E85D85] bg-white shadow-sm text-sm">
                    <button class="absolute right-2 top-1.5 bg-[#E85D85] text-white px-3 py-1.5 rounded-lg hover:bg-[#C94A72] transition text-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-[#E85D85] shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Total Requests</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-pink-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-[#E85D85] text-sm"></i>
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
                
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-purple-500 shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Processing</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['processing'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-cog text-purple-650 text-sm"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-teal-500 shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Completed</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['completed'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-teal-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-double text-teal-650 text-sm"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-4 rounded-xl border-l-4 border-red-500 shadow-sm hover:shadow transition-shadow card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Cancelled</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['cancelled'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-650 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-wrap gap-3 mb-6">
                <button onclick="filterReplacements('all')" 
                        class="filter-btn active px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-list"></i> All Requests
                </button>
                <button onclick="filterReplacements('pending')" 
                        class="filter-btn px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-clock text-yellow-500"></i> Pending
                </button>
                <button onclick="filterReplacements('approved')" 
                        class="filter-btn px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i> Approved
                </button>
                <button onclick="filterReplacements('processing')" 
                        class="filter-btn px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-cog text-purple-500"></i> Processing
                </button>
                <button onclick="filterReplacements('completed')" 
                        class="filter-btn px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-check-double text-teal-500"></i> Completed
                </button>
                <button onclick="filterReplacements('cancelled')" 
                        class="filter-btn px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-medium hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-times-circle text-red-500"></i> Cancelled
                </button>
            </div>

            <!-- Replacements Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="overflow-x-auto w-full">
                    <table class="min-w-[1100px] w-full text-left">
                        <thead class="table-header">
                            <tr>
                                <th class="text-left">Request ID</th>
                                <th class="text-left">Customer</th>
                                <th class="text-left">Product</th>
                                <th class="text-left">Reason</th>
                                <th class="text-left">Current Step</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Request Date</th>
                                <th class="text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white" id="replacementsTableBody">
                            @forelse($replacements as $replacement)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500',
                                        'approved' => 'bg-green-500',
                                        'processing' => 'bg-purple-500',
                                        'completed' => 'bg-teal-500',
                                        'cancelled' => 'bg-red-500',
                                        'rejected' => 'bg-red-500'
                                    ];
                                    
                                    $statusColor = $statusColors[$replacement->status] ?? 'bg-gray-500';
                                    
                                    $stepColors = [
                                        'request_submitted' => 'gray',
                                        'request_approved' => 'green',
                                        'shipped_to_vendor' => 'blue',
                                        'received_by_vendor' => 'purple',
                                        'replacement_verified' => 'indigo',
                                        'replacement_processing' => 'pink',
                                        'replacement_shipped' => 'red',
                                        'replacement_delivered' => 'teal',
                                        'cancelled' => 'red'
                                    ];
                                    
                                    $stepColor = $stepColors[$replacement->current_step] ?? 'gray';
                                @endphp
                                
                                <tr class="table-row replacement-row" data-replacement-id="{{ $replacement->id }}">
                                    <td class="table-cell font-bold text-black">
                                        {{-- REP-{{ str_pad($replacement->id, 6, '0', STR_PAD_LEFT) }} --}}
                                        REP-{{ str_pad($replacement->id, 6, '0', STR_PAD_LEFT) }}-{{ \Carbon\Carbon::parse($replacement->updated_at)->format('y') }}
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="flex flex-col">
                                            <span class="font-medium">
                                                {{ $replacement->customer_first_name ?? 'Customer' }} {{ $replacement->customer_last_name ?? '' }}
                                            </span>
                                            {{-- <span class="text-xs text-gray-500">{{ $replacement->customer_email }}</span> --}}
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $productImage = DB::table('vendor_product_images')
                                                    ->where('product_id', $replacement->product_id)
                                                    ->where('is_primary', 1)
                                                    ->first();
                                            @endphp
                                            @if($productImage)
                                                <img src="{{ asset('storage/vendor/products/images/' . $productImage->image_path) }}" 
                                                     class="w-10 h-10 rounded-lg object-cover border border-gray-200"
                                                     alt="{{ $replacement->product_name }}">
                                            @else
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="font-medium text-gray-800">{{ Str::limit($replacement->product_name, 30) }}</span>
                                                {{-- <div class="text-xs text-gray-500">ID: {{ $replacement->product_id }}</div> --}}
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell">
                                        <span class="text-sm capitalize">{{ str_replace('_', ' ', $replacement->reason) }}</span>
                                        {{-- @if($replacement->details)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($replacement->details, 50) }}</div>
                                        @endif --}}
                                    </td>
                                    
                                    <td class="table-cell">
                                        <span class="step-badge {{ $stepColor }} text-xs">
                                            <i class="fas fa-{{ $stepColor == 'gray' ? 'circle' : 'check-circle' }}"></i>
                                            {{ ucfirst(str_replace('_', ' ', $replacement->current_step)) }}
                                        </span>
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="relative inline-block">
                                            <span class="badge {{ $statusColor }} text-white cursor-pointer status-badge"
                                                  data-replacement-id="{{ $replacement->id }}"
                                                  data-current-status="{{ $replacement->status }}"
                                                  onclick="openStatusModal('{{ $replacement->id }}', '{{ $replacement->status }}')">
                                                {{ ucfirst($replacement->status) }}
                                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell text-gray-600">
                                        {{ \Carbon\Carbon::parse($replacement->created_at)->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($replacement->created_at)->format('h:i A') }}
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="flex gap-2">
                                            <button onclick="viewReplacement({{ $replacement->id }})"
                                                    class="action-btn bg-pink-100 text-[#C94A72] hover:bg-pink-200">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            
                                            <button onclick="openStatusModal('{{ $replacement->id }}', '{{ $replacement->status }}')"
                                                    class="action-btn bg-green-100 text-green-700 hover:bg-green-200">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-exchange-alt text-gray-400 text-2xl"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-600 mb-2">No replacement requests found</p>
                                            <p class="text-sm text-gray-500">When customers request replacements, they'll appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($replacements->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $replacements->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Status Modal -->
    <div id="statusModalOverlay" class="modal-overlay"></div>
    
    <div id="statusModal" class="status-dropdown">
        <button class="close-btn" onclick="closeStatusModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="status-dropdown-header">
            <h3 class="text-xl font-bold mb-1">Update Replacement Status</h3>
            <p class="opacity-90">Request: <span id="modalRequestId" class="font-semibold"></span></p>
        </div>
        
        <div class="status-dropdown-body">
            <!-- Current Step Info -->
            <div id="currentStepInfo" class="mb-6 p-4 bg-pink-50 rounded-lg">
                <h4 class="font-semibold text-[#C94A72] mb-2">Current Status</h4>
                <div class="flex items-center justify-between">
                    <span id="currentStepText" class="font-medium"></span>
                    <span id="currentStepBadge" class="badge"></span>
                </div>
            </div>
            
            <!-- Status Options -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-700 mb-3">Update Status</h4>
                <div class="space-y-2">
                    <div class="status-option" data-status="pending" onclick="selectStatus('pending')">
                        <div class="flex items-center">
                            <span class="badge bg-yellow-500 text-white mr-3">Pending</span>
                            <div>
                                <span class="font-medium">Pending Review</span>
                                <div class="text-xs text-gray-500">Review customer's request</div>
                            </div>
                        </div>
                        <i class="fas fa-check text-green-500 hidden selected-icon"></i>
                    </div>
                    
                    <div class="status-option" data-status="approved" onclick="selectStatus('approved')">
                        <div class="flex items-center">
                            <span class="badge bg-green-500 text-white mr-3">Approved</span>
                            <div>
                                <span class="font-medium">Approve Request</span>
                                <div class="text-xs text-gray-500">Approve the replacement</div>
                            </div>
                        </div>
                        <i class="fas fa-check text-green-500 hidden selected-icon"></i>
                    </div>
                    
                    <div class="status-option" data-status="processing" onclick="selectStatus('processing')">
                        <div class="flex items-center">
                            <span class="badge bg-purple-500 text-white mr-3">Processing</span>
                            <div>
                                <span class="font-medium">Processing</span>
                                <div class="text-xs text-gray-500">Processing replacement item</div>
                            </div>
                        </div>
                        <i class="fas fa-check text-green-500 hidden selected-icon"></i>
                    </div>
                    
                    <div class="status-option" data-status="completed" onclick="selectStatus('completed')">
                        <div class="flex items-center">
                            <span class="badge bg-teal-500 text-white mr-3">Completed</span>
                            <div>
                                <span class="font-medium">Completed</span>
                                <div class="text-xs text-gray-500">Replacement completed</div>
                            </div>
                        </div>
                        <i class="fas fa-check text-green-500 hidden selected-icon"></i>
                    </div>
                    
                    <div class="status-option" data-status="rejected" onclick="selectStatus('rejected')">
                        <div class="flex items-center">
                            <span class="badge bg-red-500 text-white mr-3">Rejected</span>
                            <div>
                                <span class="font-medium">Reject Request</span>
                                <div class="text-xs text-gray-500">Reject the replacement request</div>
                            </div>
                        </div>
                        <i class="fas fa-check text-green-500 hidden selected-icon"></i>
                    </div>
                    
                    <div class="status-option" data-status="cancelled" onclick="selectStatus('cancelled')">
                        <div class="flex items-center">
                            <span class="badge bg-red-500 text-white mr-3">Cancelled</span>
                            <div>
                                <span class="font-medium">Cancelled</span>
                                <div class="text-xs text-gray-500">Cancel the request</div>
                            </div>
                        </div>
                        <i class="fas fa-check text-green-500 hidden selected-icon"></i>
                    </div>
                </div>
            </div>
            
            <!-- Additional Options -->
            {{-- <div id="additionalOptions" class="mb-6 hidden">
                <h4 class="font-semibold text-gray-700 mb-3">Additional Information</h4>
                
                <!-- Step Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Step</label>
                    <select id="stepSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-[#E85D85]">
                        <option value="">Select Step</option>
                        <option value="request_submitted">Request Submitted</option>
                        <option value="approved">Approved</option>
                        <option value="shipped_to_vendor">Original Shipped to Vendor</option>
                        <option value="received_by_vendor">Received by Vendor</option>
                        <option value="replacement_verified">Replacement Verified</option>
                        <option value="replacement_processing">Processing Replacement</option>
                        <option value="replacement_shipped">Replacement Shipped</option>
                        <option value="replacement_delivered">Replacement Delivered</option>
                    </select>
                </div>
                
                <!-- Tracking Number -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <input type="text" id="trackingNumber" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-[#E85D85]" 
                           placeholder="Enter tracking number">
                </div>
                
                <!-- Notes -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="statusNotes" 
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-[#E85D85]" 
                              rows="3" 
                              placeholder="Add any notes about this status update..."></textarea>
                </div>
            </div> --}}
            
            <!-- Action Buttons -->
            <div class="pt-4 border-t border-gray-200">
                <button id="updateStatusBtn" onclick="updateReplacementStatus()" 
                        class="w-full py-3.5 bg-gradient-to-r from-[#FF7DA0] to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Update Status
                </button>
                <button onclick="closeStatusModal()" 
                        class="w-full py-3.5 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition mt-3">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Current replacement being edited
            let currentReplacementId = null;
            let currentReplacementStatus = null;
            let selectedStatus = null;
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#replacementsTableBody tr');
            
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
        
        // Filter replacements by status
        async function filterReplacements(status) {
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
            const tableBody = document.getElementById('replacementsTableBody');
            const originalContent = tableBody.innerHTML;
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 border-4 border-pink-200 border-t-[#E85D85] rounded-full spinner mb-4"></div>
                            <p class="text-gray-600">Loading replacements...</p>
                        </div>
                    </td>
                </tr>
            `;
            
            try {
                const response = await fetch(`/vendor/replacements/filter/${status}`);
                const html = await response.text();
                tableBody.innerHTML = html;
            } catch (error) {
                console.error('Error filtering:', error);
                tableBody.innerHTML = originalContent;
                showNotification('Error loading replacements', 'error');
            }
        }
        
        // View replacement details
        function viewReplacement(replacementId) {
            window.open(`/vendor/replacements/${replacementId}`, '_blank');
        }
        
        // Open status modal
        function openStatusModal(replacementId, currentStatus) {
            currentReplacementId = replacementId;
            currentReplacementStatus = currentStatus;
            selectedStatus = null;
            
            // Set replacement ID in modal
            document.getElementById('modalRequestId').textContent = `REP-${String(replacementId).padStart(6, '0')}`;
            
            // Set current step info
            const currentStep = document.querySelector(`[data-replacement-id="${replacementId}"] .step-badge`)?.textContent || 'Request Submitted';
            document.getElementById('currentStepText').textContent = currentStep.trim();
            document.getElementById('currentStepBadge').innerHTML = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
            document.getElementById('currentStepBadge').className = `badge ${getStatusColor(currentStatus)} text-white`;
            
            // Reset selection
            document.querySelectorAll('.status-option').forEach(option => {
                option.classList.remove('selected');
                option.querySelector('.selected-icon').classList.add('hidden');
            });
            
            // Highlight current status
            const currentOption = document.querySelector(`.status-option[data-status="${currentStatus}"]`);
            if (currentOption) {
                currentOption.classList.add('selected');
                currentOption.querySelector('.selected-icon').classList.remove('hidden');
            }
            
            // Reset additional options
            // document.getElementById('stepSelect').value = '';
            // document.getElementById('trackingNumber').value = '';
            // document.getElementById('statusNotes').value = '';
            // document.getElementById('additionalOptions').classList.add('hidden');
            
            // Disable update button initially
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
                'approved': 'bg-green-500',
                'processing': 'bg-purple-500',
                'completed': 'bg-teal-500',
                'cancelled': 'bg-red-500',
                'rejected': 'bg-red-500'
            };
            return colors[status] || 'bg-gray-500';
        }
        
        // Close status modal
        function closeStatusModal() {
            document.getElementById('statusModalOverlay').classList.remove('open');
            document.getElementById('statusModal').classList.remove('open');
            document.body.classList.remove('modal-open');
            
            currentReplacementId = null;
            currentReplacementStatus = null;
            selectedStatus = null;
        }
        
        // Select status option
        function selectStatus(status) {
            selectedStatus = status;
            
            // Update UI
            document.querySelectorAll('.status-option').forEach(option => {
                option.classList.remove('selected');
                option.querySelector('.selected-icon').classList.add('hidden');
            });
            
            const selectedOption = document.querySelector(`.status-option[data-status="${status}"]`);
            if (selectedOption) {
                selectedOption.classList.add('selected');
                selectedOption.querySelector('.selected-icon').classList.remove('hidden');
            }
            
            // Show/hide additional options based on status
            // const additionalOptions = document.getElementById('additionalOptions');
            // if (['approved', 'processing', 'completed'].includes(status)) {
            //     additionalOptions.classList.remove('hidden');
            // } else {
            //     additionalOptions.classList.add('hidden');
            // }
            
            // Enable/disable update button
            const updateBtn = document.getElementById('updateStatusBtn');
            if (status === currentReplacementStatus) {
                updateBtn.disabled = true;
                updateBtn.textContent = 'Update Status';
            } else {
                updateBtn.disabled = false;
                updateBtn.textContent = `Update to ${status.charAt(0).toUpperCase() + status.slice(1)}`;
            }
        }
        
        // Update replacement status
        async function updateReplacementStatus() {
            if (!currentReplacementId || !selectedStatus || selectedStatus === currentReplacementStatus) {
                return;
            }
            
            const badge = document.querySelector(`.status-badge[data-replacement-id="${currentReplacementId}"]`);
            const stepBadge = document.querySelector(`[data-replacement-id="${currentReplacementId}"] .step-badge`);
            const updateBtn = document.getElementById('updateStatusBtn');
            
            // Show loading state
            const originalText = updateBtn.innerHTML;
            updateBtn.innerHTML = '<i class="fas fa-spinner spinner mr-2"></i> Updating...';
            updateBtn.disabled = true;
            
            // Prepare data
            const data = {
                replacement_id: currentReplacementId,
                status: selectedStatus,
                current_step: '',
                tracking_number: '',
                notes: '',
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            
            try {
                const response = await fetch("{{ route('vendor.replacements.update-status') }}", {
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
                    // Update UI immediately
                    if (badge) {
                        badge.className = `badge ${getStatusColor(selectedStatus)} text-white cursor-pointer status-badge`;
                        badge.innerHTML = `${selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1)} <i class="fas fa-chevron-down ml-1 text-xs"></i>`;
                        badge.setAttribute('data-current-status', selectedStatus);
                    }
                    
                    if (stepBadge && data.current_step) {
                        const stepText = data.current_step.replace(/_/g, ' ');
                        stepBadge.innerHTML = `<i class="fas fa-check-circle"></i> ${stepText.charAt(0).toUpperCase() + stepText.slice(1)}`;
                        stepBadge.className = `step-badge completed text-xs`;
                    }
                    
                    showNotification('Status updated successfully!', 'success');
                    
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
</body>
</html>