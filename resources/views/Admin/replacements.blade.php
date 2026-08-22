<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Replacement Requests</title>
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
                        <i class="fas fa-arrows-rotate"></i>
                    </span>
                    Replacement Requests
                </h1>
                <p class="text-sm text-gray-500 mt-1">Customer product replacement requests — update status inline.</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
            @php
                $cards = [
                    ['label' => 'Total',     'value' => $stats['total'],     'icon' => 'fa-layer-group',   'cls' => 'text-white', 'grad' => true],
                    ['label' => 'Pending',   'value' => $stats['pending'],   'icon' => 'fa-hourglass-half', 'cls' => 'bg-amber-100 text-amber-600'],
                    ['label' => 'Approved',  'value' => $stats['approved'],  'icon' => 'fa-thumbs-up',     'cls' => 'bg-blue-100 text-blue-600'],
                    ['label' => 'Completed', 'value' => $stats['completed'], 'icon' => 'fa-circle-check',  'cls' => 'bg-green-100 text-green-600'],
                    ['label' => 'Rejected',  'value' => $stats['rejected'],  'icon' => 'fa-ban',           'cls' => 'bg-red-100 text-red-500'],
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

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[1000px]">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wide text-gray-500 bg-[#FFF6F0]/70">
                            <th class="px-5 py-3.5">#</th>
                            <th class="px-5 py-3.5">Customer</th>
                            <th class="px-5 py-3.5">Product</th>
                            <th class="px-5 py-3.5">Vendor</th>
                            <th class="px-5 py-3.5">Reason</th>
                            <th class="px-5 py-3.5">Details</th>
                            <th class="px-5 py-3.5">Vendor Comment</th>
                            <th class="px-5 py-3.5">Courier Cost</th>
                            <th class="px-5 py-3.5">Total Payable</th>
                            <th class="px-5 py-3.5">Current Step</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rows as $r)
                            @php $st = $r->status ?: 'pending'; @endphp
                            <tr class="hover:bg-[#FFF6F0]/50 transition">
                                <td class="px-5 py-3.5 text-gray-400">{{ $r->id }}</td>
                                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $r->customer_name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-700">{{ \Illuminate\Support\Str::limit($r->product_name ?? 'Product removed', 30) }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $r->vendor_name ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-pink-50 text-brand whitespace-nowrap">
                                        {{ ucwords(str_replace('_', ' ', $r->reason ?? '—')) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 max-w-[200px]">
                                    @if(!empty($r->details))
                                        <span class="block truncate" title="{{ $r->details }}">{{ $r->details }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 max-w-[200px]">
                                    @if(!empty($r->vendor_comment))
                                        <span class="block truncate" title="{{ $r->vendor_comment }}">{{ $r->vendor_comment }}</span>
                                    @else
                                        <span class="text-gray-300 italic">No comment yet</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($r->courier_paid_by === 'vendor')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-red-50 text-red-600">Vendor pays</span>
                                    @elseif($r->courier_paid_by === 'customer')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-green-50 text-green-600">Customer pays</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-gray-800 whitespace-nowrap">
                                    {{ isset($r->total_amount_payable) && $r->total_amount_payable !== null ? 'Rs. ' . number_format($r->total_amount_payable, 2) : '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if(!empty($r->current_step))
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-600 whitespace-nowrap">
                                            <i class="fas fa-shoe-prints mr-1 text-[10px]"></i>{{ ucwords(str_replace('_', ' ', $r->current_step)) }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap">
                                    {{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d M Y') : '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <select class="status-select text-xs font-semibold rounded-full px-3 py-1.5 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-brand/40"
                                            data-id="{{ $r->id }}" data-prev="{{ $st }}"
                                            onchange="changeStatus(this)">
                                        @foreach(['pending', 'approved', 'rejected', 'processing', 'completed', 'cancelled'] as $s)
                                            <option value="{{ $s }}" {{ $st === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="12" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>No replacement requests yet.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
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

    const STATUS_STYLE = {
        'pending':    ['#FEF3C7', '#B45309', '#FDE68A'],
        'approved':   ['#DBEAFE', '#1D4ED8', '#BFDBFE'],
        'rejected':   ['#FEE2E2', '#B91C1C', '#FECACA'],
        'processing': ['#EDE9FE', '#6D28D9', '#DDD6FE'],
        'completed':  ['#DCFCE7', '#15803D', '#BBF7D0'],
        'cancelled':  ['#F3F4F6', '#4B5563', '#E5E7EB'],
    };
    const STEP_BY_STATUS = {
        'pending': 'Request Submitted',
        'approved': 'Request Approved',
        'rejected': 'Rejected',
        'processing': 'Replacement Processing',
        'completed': 'Replacement Delivered',
        'cancelled': 'Cancelled',
    };
    function paintSelect(sel) {
        const [bg, fg, bd] = STATUS_STYLE[sel.value] || ['#F3F4F6', '#374151', '#E5E7EB'];
        sel.style.background = bg; sel.style.color = fg; sel.style.borderColor = bd;
    }
    document.querySelectorAll('.status-select').forEach(paintSelect);

    async function changeStatus(sel) {
        const prev = sel.dataset.prev, next = sel.value;
        if (prev === next) return;
        sel.disabled = true;
        const res = await post('/admin/replacements/' + sel.dataset.id + '/status', { status: next });
        sel.disabled = false;
        if (res.success) {
            sel.dataset.prev = next;
            paintSelect(sel);
            // sync the current-step chip text with the step the backend writes
            const stepCell = sel.closest('tr').querySelector('td:nth-child(7) span');
            if (stepCell && STEP_BY_STATUS[next]) {
                stepCell.innerHTML = '<i class="fas fa-shoe-prints mr-1 text-[10px]"></i>' + STEP_BY_STATUS[next];
                stepCell.className = 'px-2.5 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-600 whitespace-nowrap';
            }
            toast(res.message || 'Replacement status updated.');
        } else {
            sel.value = prev;
            paintSelect(sel);
            toast(res.message || 'Update failed.', false);
        }
    }
</script>
</body>
</html>
