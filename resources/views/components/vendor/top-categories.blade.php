@props(['topCategories'])

<div class="bg-white p-4 rounded shadow">
    <h2 class="text-lg font-bold mb-4">Top 5 Sales Categories</h2>
    <ul class="space-y-2 text-sm text-gray-700">
        @foreach($topCategories as $category)
            <li class="flex justify-between">
                <span>{{ $category['name'] }}</span>
                <span class="font-bold">{{ $category['order_count'] }}</span>
            </li>
        @endforeach
    </ul>
</div>