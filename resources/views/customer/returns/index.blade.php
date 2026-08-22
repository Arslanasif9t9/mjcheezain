<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Returns | MJ Cheezain</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-customer.theme />
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 sm:p-6 max-w-6xl">
        <div class="app-card overflow-hidden">
            <!-- Header -->
            <div class="brand-gradient p-4 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">My Return Requests</h1>
                        <p class="opacity-90">Track and manage all your product returns</p>
                    </div>
                    <a href="{{ route('customer.orders') }}" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg w-full sm:w-auto text-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-8">
                @if($returns->count() > 0)
                    <div class="space-y-4">
                        @foreach($returns as $return)
                            @php
                                $statusStyles = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'processing' => 'bg-pink-100 text-[#E85D85]',
                                    'refunded' => 'bg-teal-100 text-teal-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-gray-100 text-gray-600',
                                ];
                                $statusStyle = $statusStyles[$return->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <div class="border border-pink-100 rounded-xl p-4 sm:p-5 hover:shadow-md transition-shadow bg-white">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <!-- Product image -->
                                    @if($return->product_image)
                                        <img src="{{ asset('storage/vendor/products/images/' . $return->product_image) }}"
                                             class="w-16 h-16 rounded-xl object-cover border border-gray-200 flex-shrink-0"
                                             alt="{{ $return->product_name }}">
                                    @else
                                        <div class="w-16 h-16 rounded-xl brand-gradient-soft flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-box text-[#E85D85]"></i>
                                        </div>
                                    @endif

                                    <!-- Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="text-xs font-bold text-[#E85D85] tracking-wide">
                                                {{ $return->return_number ?? ('RET-' . str_pad($return->id, 6, '0', STR_PAD_LEFT)) }}
                                            </span>
                                            <span class="px-3 py-0.5 rounded-full text-xs font-semibold capitalize {{ $statusStyle }}">
                                                {{ $return->status }}
                                            </span>
                                        </div>
                                        <p class="font-bold text-gray-800 truncate">{{ $return->product_name }}</p>
                                        <p class="text-sm text-gray-500 mt-0.5">
                                            <span class="capitalize">{{ str_replace('_', ' ', $return->reason) }}</span>
                                            &middot; Qty: {{ $return->quantity }}
                                            &middot; {{ \Carbon\Carbon::parse($return->created_at)->format('M d, Y') }}
                                        </p>
                                    </div>

                                    <!-- Refund + action -->
                                    <div class="flex items-center justify-between sm:flex-col sm:items-end gap-2 flex-shrink-0">
                                        <p class="font-bold text-gray-800">Rs. {{ number_format($return->refund_amount, 2) }}</p>
                                        <a href="{{ route('customer.returns.track', $return->id) }}"
                                           class="px-4 py-2 brand-gradient brand-shadow text-white rounded-full text-sm font-semibold hover:opacity-90">
                                            <i class="fas fa-truck mr-1"></i> Track
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($returns->hasPages())
                        <div class="mt-6">
                            {{ $returns->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-4">
                            <i class="fas fa-undo text-2xl text-[#E85D85]"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-700 mb-1">No return requests yet</p>
                        <p class="text-sm text-gray-500 mb-6">When you request a return from a delivered order, it will appear here.</p>
                        <a href="{{ route('customer.orders') }}"
                           class="inline-block px-6 py-2.5 brand-gradient brand-shadow text-white rounded-full font-semibold hover:opacity-90">
                            View My Orders
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
