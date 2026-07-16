<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Category Requests</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <x-admin.sidebar />

        <div class="flex-1 p-6 overflow-x-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-tags mr-2"></i> Vendor Category Requests
                </h2>
                <span class="bg-amber-100 text-amber-700 px-4 py-1.5 rounded-full text-sm font-semibold">
                    {{ $pendingCount }} pending
                </span>
            </div>

            <p class="text-gray-500 text-sm mb-6">
                Categories typed by vendors via the "Other" option on the Add Product form.
                Review them here — products using these categories are live under the typed name once approved.
            </p>

            @if($suggestions->isEmpty())
                <div class="bg-white rounded-xl shadow p-10 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                    <p>No category requests yet.</p>
                </div>
            @else
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3 text-left">#</th>
                                    <th class="px-4 py-3 text-left">Category</th>
                                    <th class="px-4 py-3 text-left">Subcategory</th>
                                    <th class="px-4 py-3 text-left">Vendor</th>
                                    <th class="px-4 py-3 text-left">First Product</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($suggestions as $i => $s)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $s->category_name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $s->subcategory_name ?: '—' }}</td>
                                        <td class="px-4 py-3">
                                            <div class="text-gray-800">{{ $s->vendor_name ?: 'Unknown' }}</div>
                                            <div class="text-gray-400 text-xs">{{ $s->vendor_email }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($s->product_id && $s->product_name)
                                                <a href="/product/{{ $s->product_id }}" target="_blank" class="text-blue-600 hover:underline">
                                                    {{ \Illuminate\Support\Str::limit($s->product_name, 30) }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($s->created_at)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $badge = match($s->status) {
                                                    'pending' => 'bg-amber-100 text-amber-700',
                                                    'added' => 'bg-green-100 text-green-700',
                                                    'rejected' => 'bg-red-100 text-red-600',
                                                    default => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                                {{ ucfirst($s->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
