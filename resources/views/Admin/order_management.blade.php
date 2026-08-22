<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Management</title>
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
                        <i class="fas fa-box-open"></i>
                    </span>
                    Order Management
                </h1>
                <p class="text-sm text-gray-500 mt-1">All order line items across vendors — update fulfillment status inline.</p>
            </div>
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input id="orderSearch" type="text" placeholder="Search order, product, customer, vendor…"
                       class="pl-10 pr-4 py-2.5 w-80 max-w-full rounded-xl bg-white shadow-card text-sm focus:outline-none focus:ring-2 focus:ring-brand/40" />
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            @php
                $cards = [
                    ['label' => 'Total Items',  'value' => $stats['total'],      'icon' => 'fa-layer-group', 'cls' => 'text-white', 'grad' => true],
                    ['label' => 'Placed',       'value' => $stats['placed'],     'icon' => 'fa-receipt',     'cls' => 'bg-amber-100 text-amber-600'],
                    ['label' => 'Processing',   'value' => $stats['processing'], 'icon' => 'fa-gears',       'cls' => 'bg-blue-100 text-blue-600'],
                    ['label' => 'Shipping',     'value' => $stats['shipping'],   'icon' => 'fa-truck-fast',  'cls' => 'bg-purple-100 text-purple-600'],
                    ['label' => 'Delivered',    'value' => $stats['delivered'],  'icon' => 'fa-circle-check','cls' => 'bg-green-100 text-green-600'],
                    ['label' => 'Cancelled',    'value' => $stats['cancelled'],  'icon' => 'fa-ban',         'cls' => 'bg-red-100 text-red-500'],
                ];
            @endphp
            @foreach($cards as $c)
                <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $c['cls'] }}"
                         @if(!empty($c['grad'])) style="background:linear-gradient(115deg,#FF7DA0,#FFC275)" @endif>
                        <i class="fas {{ $c['icon'] }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xl font-bold text-gray-800 leading-tight">{{ number_format($c['value']) }}</div>
                        <div class="text-[11px] text-gray-500 truncate">{{ $c['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filter chips -->
        <div class="flex flex-wrap gap-2 mb-4" id="statusChips">
            @foreach(array_merge(['all'], $statuses) as $chip)
                <button data-chip="{{ $chip }}"
                        class="chip px-4 py-1.5 rounded-full text-xs font-semibold transition border
                               {{ $chip === 'all' ? 'text-white border-transparent shadow' : 'bg-white text-gray-600 border-gray-200 hover:border-brand hover:text-brand' }}"
                        @if($chip === 'all') style="background:linear-gradient(115deg,#FF7DA0,#FFC275)" @endif>
                    {{ $chip === 'all' ? 'All' : ucwords($chip) }}
                </button>
            @endforeach
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wide text-gray-500 bg-[#FFF6F0]/70">
                            <th class="px-5 py-3.5">Order</th>
                            <th class="px-5 py-3.5">Product</th>
                            <th class="px-5 py-3.5">Customer</th>
                            <th class="px-5 py-3.5">Vendor</th>
                            <th class="px-5 py-3.5 text-center">Qty</th>
                            <th class="px-5 py-3.5">Price</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="ordersBody">
                        @forelse($orders as $o)
                            @php $st = $o->status ?: 'order placed'; @endphp
                            <tr class="order-row hover:bg-[#FFF6F0]/50 transition" data-status="{{ $st }}">
                                <td class="px-5 py-3.5 font-semibold text-gray-700 whitespace-nowrap">{{ \App\Support\RefId::order($o->order_id) }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3 min-w-[180px]">
                                        <img src="/storage/vendor/products/images/{{ $o->product_image }}" alt=""
                                             class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0"
                                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <span class="w-10 h-10 rounded-lg bg-pink-50 text-brand items-center justify-center shrink-0 hidden"><i class="fas fa-image"></i></span>
                                        <span class="font-medium text-gray-800">{{ \Illuminate\Support\Str::limit($o->product_name ?? 'Product removed', 34) }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="text-gray-800">{{ $o->customer_name ?? '—' }}</div>
                                    <div class="text-[11px] text-gray-400">{{ $o->customer_email }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $o->vendor_name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-center text-gray-700">{{ $o->quantity }}</td>
                                <td class="px-5 py-3.5 font-semibold text-gray-800 whitespace-nowrap">Rs. {{ number_format($o->price) }}</td>
                                <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($o->created_at)->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <select class="status-select text-xs font-semibold rounded-full px-3 py-1.5 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-brand/40"
                                            data-cart-id="{{ $o->id }}" data-prev="{{ $st }}"
                                            onchange="changeStatus(this)">
                                        @foreach($statuses as $s)
                                            <option value="{{ $s }}" {{ $st === $s ? 'selected' : '' }}>{{ ucwords($s) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>No orders yet.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="noResults" class="hidden px-5 py-10 text-center text-gray-400 text-sm">
                <i class="fas fa-magnifying-glass text-2xl mb-2 block text-gray-300"></i>No orders match your search / filter.
            </div>
        </div>
    </main>
</div>

<script>
    /* ---------- helpers ---------- */
    function toast(msg, ok = true) {
        const t = document.createElement('div');
        t.className = 'fixed bottom-6 right-6 z-[9999] px-5 py-3 rounded-xl shadow-lg text-white text-sm font-medium flex items-center gap-2 ' + (ok ? '' : 'bg-red-500');
        if (ok) t.style.background = 'linear-gradient(115deg,#FF7DA0,#FFC275)';
        t.innerHTML = '<i class="fas ' + (ok ? 'fa-check-circle' : 'fa-exclamation-circle') + '"></i><span></span>';
        t.querySelector('span').textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => { t.style.transition = 'opacity .4s'; t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 2500);
    }
    async function post(url, body = {}) {
        try {
            const r = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(body)
            });
            return await r.json();
        } catch (e) { return { success: false, message: 'Network error — please retry.' }; }
    }

    /* ---------- status select styling ---------- */
    const STATUS_STYLE = {
        'order placed': ['#FEF3C7', '#B45309', '#FDE68A'],
        'processing':   ['#DBEAFE', '#1D4ED8', '#BFDBFE'],
        'shipping':     ['#EDE9FE', '#6D28D9', '#DDD6FE'],
        'delivered':    ['#DCFCE7', '#15803D', '#BBF7D0'],
        'cancelled':    ['#FEE2E2', '#B91C1C', '#FECACA'],
    };
    function paintSelect(sel) {
        const [bg, fg, bd] = STATUS_STYLE[sel.value] || ['#F3F4F6', '#374151', '#E5E7EB'];
        sel.style.background = bg; sel.style.color = fg; sel.style.borderColor = bd;
    }
    document.querySelectorAll('.status-select').forEach(paintSelect);

    /* ---------- inline status update ---------- */
    async function changeStatus(sel) {
        const prev = sel.dataset.prev, next = sel.value;
        if (prev === next) return;
        sel.disabled = true;
        const res = await post('/admin/orders/update-status', { cart_id: parseInt(sel.dataset.cartId), status: next });
        sel.disabled = false;
        if (res.success) {
            sel.dataset.prev = next;
            sel.closest('tr').dataset.status = next;
            paintSelect(sel);
            toast(res.message || 'Status updated.');
        } else {
            sel.value = prev;
            paintSelect(sel);
            toast(res.message || 'Update failed.', false);
        }
    }

    /* ---------- search + chip filter (client-side) ---------- */
    let activeChip = 'all';
    const searchInput = document.getElementById('orderSearch');

    function applyFilters() {
        const q = searchInput.value.trim().toLowerCase();
        let visible = 0;
        document.querySelectorAll('.order-row').forEach(tr => {
            const chipOk = activeChip === 'all' || tr.dataset.status === activeChip;
            const textOk = !q || tr.textContent.toLowerCase().includes(q);
            const show = chipOk && textOk;
            tr.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        const rows = document.querySelectorAll('.order-row').length;
        document.getElementById('noResults').classList.toggle('hidden', visible > 0 || rows === 0);
    }
    searchInput.addEventListener('input', applyFilters);

    document.querySelectorAll('#statusChips .chip').forEach(btn => {
        btn.addEventListener('click', () => {
            activeChip = btn.dataset.chip;
            document.querySelectorAll('#statusChips .chip').forEach(b => {
                const on = b === btn;
                b.classList.toggle('text-white', on);
                b.classList.toggle('border-transparent', on);
                b.classList.toggle('shadow', on);
                b.classList.toggle('bg-white', !on);
                b.classList.toggle('text-gray-600', !on);
                b.classList.toggle('border-gray-200', !on);
                b.style.background = on ? 'linear-gradient(115deg,#FF7DA0,#FFC275)' : '';
            });
            applyFilters();
        });
    });
</script>
</body>
</html>
