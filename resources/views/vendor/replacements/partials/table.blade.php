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
            'shipped_to_vendor' => 'purple',
            'received_by_vendor' => 'purple',
            'replacement_verified' => 'indigo',
            'replacement_processing' => 'pink',
            'replacement_shipped' => 'red',
            'replacement_delivered' => 'teal',
            'cancelled' => 'red'
        ];
        
        $stepColor = $stepColors[$replacement->current_step] ?? 'gray';
    @endphp
    
    {{-- data-status / data-step are read by the mobile app-card renderer on vendor/replacements/index --}}
    <tr class="table-row replacement-row" data-replacement-id="{{ $replacement->id }}"
        data-status="{{ $replacement->status }}"
        data-step="{{ $replacement->current_step }}">
        <td class="table-cell font-bold text-[#E85D85]">
            REP-{{ str_pad($replacement->id, 6, '0', STR_PAD_LEFT) }}
        </td>
        
        <td class="table-cell">
            <div class="flex flex-col">
                <span class="font-medium">
                    {{ $replacement->customer_first_name ?? 'Customer' }} {{ $replacement->customer_last_name ?? '' }}
                </span>
                <span class="text-xs text-gray-500">{{ $replacement->customer_email }}</span>
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
                    <div class="text-xs text-gray-500">ID: {{ $replacement->product_id }}</div>
                </div>
            </div>
        </td>
        
        <td class="table-cell">
            <span class="text-sm capitalize">{{ str_replace('_', ' ', $replacement->reason) }}</span>
            @if($replacement->details)
                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($replacement->details, 50) }}</div>
            @endif
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
                <a href="{{ route('vendor.replacements.show', $replacement->id) }}"
                   class="action-btn bg-pink-100 text-pink-700 hover:bg-pink-200">
                    <i class="fas fa-eye"></i> View
                </a>
                
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
                <p class="text-sm text-gray-500">No replacements match the selected filter.</p>
            </div>
        </td>
    </tr>
@endforelse