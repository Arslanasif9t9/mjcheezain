@props(['recentOrders'])

<div class="lg:col-span-2 app-card p-4 md:p-6">
    <h2 class="text-base md:text-lg font-bold mb-4 text-gray-800">Recent Sold</h2>

    {{-- Desktop table --}}
    <div class="hidden md:block overflow-x-auto w-full">
        <table class="min-w-[650px] w-full text-left">
            <thead>
                <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-100">
                    <th class="pb-3">Image</th>
                    <th class="pb-3">Product</th>
                    <th class="pb-3">Category</th>
                    <th class="pb-3">Amount</th>
                    <th class="pb-3">Date</th>
                    <th class="pb-3">Customer</th>
                    <th class="pb-3">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600 divide-y divide-gray-100">
                @foreach($recentOrders as $order)
                    @php
                        $statusColor = match(strtolower($order['fulfillment'])) {
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                            'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                            'shipped' => 'bg-purple-50 text-purple-700 border-purple-100',
                            'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                            default => 'bg-gray-50 text-gray-700 border-gray-100',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5">
                            <img class="w-12 h-12 rounded-lg object-cover border border-gray-100"
                                 src="{{ $order['image_path'] ? asset('storage/vendor/products/images/' . $order['image_path']) : asset('img/default-product.jpg') }}"
                                 alt="Product Image">
                        </td>
                        <td class="py-3.5 font-medium text-gray-850">{{ $order['product_name'] }}</td>
                        <td class="py-3.5 text-gray-500">{{ $order['product_category'] }}</td>
                        <td class="py-3.5 font-semibold text-gray-800">{{ number_format($order['total_amount'], 2) }} PKR</td>
                        <td class="py-3.5 text-gray-550">{{ date('d/m/Y', strtotime($order['order_date'])) }}</td>
                        <td class="py-3.5 text-gray-550">{{ $order['customer_name'] ?: 'N/A' }}</td>
                        <td class="py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                {{ ucfirst($order['fulfillment']) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile card list --}}
    <div class="md:hidden space-y-3">
        @forelse($recentOrders as $order)
            @php
                $statusColorM = match(strtolower($order['fulfillment'])) {
                    'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                    'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                    'shipped' => 'bg-purple-50 text-purple-700 border-purple-100',
                    'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                    'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                    default => 'bg-gray-50 text-gray-700 border-gray-100',
                };
            @endphp
            <div class="rounded-2xl border border-pink-100 bg-white p-3.5">
                <div class="flex items-center">
                    <img class="w-12 h-12 rounded-xl object-cover border border-pink-50 flex-shrink-0"
                         src="{{ $order['image_path'] ? asset('storage/vendor/products/images/' . $order['image_path']) : asset('img/default-product.jpg') }}"
                         alt="Product Image">
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ $order['product_name'] }}</p>
                        <p class="text-[11px] text-gray-400 truncate">{{ $order['product_category'] }} &middot; {{ date('d/m/Y', strtotime($order['order_date'])) }}</p>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5"><i class="fas fa-user text-[9px] mr-1 text-gray-300"></i>{{ $order['customer_name'] ?: 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-pink-50">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $statusColorM }}">
                        {{ ucfirst($order['fulfillment']) }}
                    </span>
                    <span class="text-sm font-extrabold text-gray-800">Rs. {{ number_format($order['total_amount'], 2) }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <div class="w-14 h-14 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-2">
                    <i class="fas fa-box-open text-xl text-[#E85D85]"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium">No recent sales yet</p>
            </div>
        @endforelse
    </div>
</div>
