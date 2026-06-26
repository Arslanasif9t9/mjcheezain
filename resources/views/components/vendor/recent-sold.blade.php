@props(['recentOrders'])

<div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h2 class="text-lg font-bold mb-4 text-gray-800">Recent Sold</h2>
    <div class="overflow-x-auto w-full">
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
                            'shipped' => 'bg-blue-50 text-blue-700 border-blue-100',
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
</div>