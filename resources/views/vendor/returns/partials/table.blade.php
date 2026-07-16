{{-- Rows only: injected into #returnsTableBody by filterReturns() on vendor/returns/index.
     Keep cell order + classes + data attributes identical to the index table rows —
     renderMobileReturnCards() re-reads these cells to rebuild the mobile card list. --}}
@forelse($returns as $return)
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

    <tr class="table-row return-row" data-return-id="{{ $return->id }}">
        <td class="table-cell font-bold text-orange-600">
            RET-{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}-{{ \Carbon\Carbon::parse($return->updated_at)->format('y') }}
        </td>

        <td class="table-cell">
            <div class="flex flex-col">
                <span class="font-medium">
                    {{ $return->customer_first_name ?? 'Customer' }} {{ $return->customer_last_name ?? '' }}
                </span>
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
                    <i class="fas fa-undo text-gray-400 text-2xl"></i>
                </div>
                <p class="text-lg font-medium text-gray-600 mb-2">No return requests found</p>
                <p class="text-sm text-gray-500">When customers request returns, they'll appear here.</p>
            </div>
        </td>
    </tr>
@endforelse
