<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Customers</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: {
            colors: { brand: '#E85D85' },
            fontFamily: { sans: ['Poppins', 'sans-serif'] },
            boxShadow: { card: '0 4px 20px rgba(232,93,133,.08)' }
        } } };
    </script>
    <style>body{font-family:'Poppins',sans-serif}</style>
</head>
<body class="bg-[#FFF6F0]">
<div class="flex min-h-screen">
    <x-admin.sidebar />

    <main class="flex-1 min-w-0 p-6 lg:p-8">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-11 h-11 rounded-xl flex items-center justify-center text-white" style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                        <i class="fas fa-users"></i>
                    </span>
                    Customers
                </h1>
                <p class="text-sm text-gray-500 mt-1">All registered customers on the store (read-only).</p>
            </div>
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input id="customerSearch" type="text" placeholder="Search name, email, phone…"
                       class="pl-10 pr-4 py-2.5 w-80 max-w-full rounded-xl bg-white shadow-card text-sm focus:outline-none focus:ring-2 focus:ring-brand/40" />
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shrink-0" style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                    <i class="fas fa-user-group"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800 leading-tight">{{ number_format($stats['total']) }}</div>
                    <div class="text-xs text-gray-500">Total Customers</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-green-100 text-green-600 shrink-0">
                    <i class="fas fa-bag-shopping"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800 leading-tight">{{ number_format($stats['with_orders']) }}</div>
                    <div class="text-xs text-gray-500">With Orders</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-blue-100 text-blue-600 shrink-0">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800 leading-tight">{{ number_format($stats['new_30d']) }}</div>
                    <div class="text-xs text-gray-500">New (last 30 days)</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[800px]">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wide text-gray-500 bg-[#FFF6F0]/70">
                            <th class="px-5 py-3.5">Customer</th>
                            <th class="px-5 py-3.5">Email</th>
                            <th class="px-5 py-3.5">Phone</th>
                            <th class="px-5 py-3.5 text-center">Orders</th>
                            <th class="px-5 py-3.5">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rows as $r)
                            <tr class="customer-row hover:bg-[#FFF6F0]/50 transition">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if(!empty($r->profile_image))
                                            <img src="/storage/customer/profile/{{ $r->profile_image }}" alt=""
                                                 class="w-10 h-10 rounded-full object-cover bg-gray-100 shrink-0"
                                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                            <span class="w-10 h-10 rounded-full text-white font-semibold text-sm items-center justify-center shrink-0 hidden" style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                                                {{ strtoupper(substr($r->full_name ?? '?', 0, 1)) }}
                                            </span>
                                        @else
                                            <span class="w-10 h-10 rounded-full text-white font-semibold text-sm flex items-center justify-center shrink-0" style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                                                {{ strtoupper(substr($r->full_name ?? '?', 0, 1)) }}
                                            </span>
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $r->full_name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $r->email ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">{{ $r->phone ?: '—' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($r->order_count > 0)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-pink-50 text-brand">{{ $r->order_count }}</span>
                                    @else
                                        <span class="text-gray-300">0</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap">
                                    {{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d M Y') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>No customers yet.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="noResults" class="hidden px-5 py-10 text-center text-gray-400 text-sm">
                <i class="fas fa-magnifying-glass text-2xl mb-2 block text-gray-300"></i>No customers match your search.
            </div>
        </div>
    </main>
</div>

<script>
    const searchInput = document.getElementById('customerSearch');
    searchInput.addEventListener('input', () => {
        const q = searchInput.value.trim().toLowerCase();
        let visible = 0;
        document.querySelectorAll('.customer-row').forEach(tr => {
            const show = !q || tr.textContent.toLowerCase().includes(q);
            tr.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        const rows = document.querySelectorAll('.customer-row').length;
        document.getElementById('noResults').classList.toggle('hidden', visible > 0 || rows === 0);
    });
</script>
</body>
</html>
