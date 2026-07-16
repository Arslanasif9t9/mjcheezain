@props(['topCategories'])

<div class="app-card p-4 md:p-6">
    <h2 class="text-base md:text-lg font-bold mb-4 text-gray-800 border-b border-gray-50 pb-3">Top 5 Sales Categories</h2>
    <ul class="space-y-4">
        @foreach($topCategories as $category)
            <li class="flex flex-col gap-1.5">
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-700">{{ $category['name'] }}</span>
                    <span class="font-bold text-gray-900 bg-gray-50 px-2 py-0.5 rounded text-xs border border-gray-100">{{ $category['order_count'] }} orders</span>
                </div>
                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    @php
                        // calculate simple progress width (max is usually the first item or let's say 20 orders limit for full bar)
                        $maxOrders = max(array_column($topCategories, 'order_count')) ?: 1;
                        $percent = min(100, ($category['order_count'] / $maxOrders) * 100);
                    @endphp
                    <div class="h-full rounded-full" style="background: linear-gradient(90deg, #FF7DA0, #FFC275); width: {{ $percent }}%"></div>
                </div>
            </li>
        @endforeach
    </ul>
</div>