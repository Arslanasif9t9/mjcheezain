@props(['recentOrders'])

<div class="lg:col-span-2 bg-white p-4 rounded shadow">
    <h2 class="text-lg font-bold mb-4">Recent Sold</h2>
    <table class="w-full text-left">
        <thead>
            <tr class="text-sm text-gray-600">
                <th>Image</th>
                <th class="py-2">Product</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody class="text-sm text-gray-700">
            @foreach($recentOrders as $order)
                @php
                    $statusColor = match(strtolower($order['fulfillment'])) {
                        'pending' => 'text-yellow-600',
                        'processing' => 'text-purple-600',
                        'shipped' => 'text-yellow-600',
                        'delivered' => 'text-green-600',
                        'cancelled' => 'text-red-600',
                        default => 'text-gray-600',
                    };
                @endphp
                <tr class="border-t">
                    <td class="py-2">
                        <img style="width:50px; height:50px; border-radius:10px;" 
                             src="{{ $order['image_path'] ?: 'uploads/default_product.webp' }}" 
                             alt="Product Image">
                    </td>
                    <td class="py-2">{{ $order['product_name'] }}</td>
                    <td>{{ $order['product_category'] }}</td>
                    <td>{{ number_format($order['total_amount'], 2) }} TK</td>
                    <td>{{ date('d/m/Y', strtotime($order['order_date'])) }}</td>
                    <td>{{ $order['customer_name'] ?: 'N/A' }}</td>
                    <td><span class="{{ $statusColor }}">{{ ucfirst($order['fulfillment']) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>