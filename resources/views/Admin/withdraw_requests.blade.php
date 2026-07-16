<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Withdraw Requests</title>
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
                        <i class="fas fa-money-bill-transfer"></i>
                    </span>
                    Withdraw Requests
                </h1>
                <p class="text-sm text-gray-500 mt-1">Vendor payout requests — approve, reject or mark paid.</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white shrink-0" style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">{{ number_format($stats['pending']) }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Pending Requests</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-amber-100 text-amber-600 shrink-0">
                    <i class="fas fa-sack-dollar"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">Rs. {{ number_format($stats['pending_amount']) }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Pending Amount</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-blue-100 text-blue-600 shrink-0">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">{{ number_format($stats['approved']) }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Approved</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-green-100 text-green-600 shrink-0">
                    <i class="fas fa-circle-check"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">{{ number_format($stats['paid']) }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Paid</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-red-100 text-red-500 shrink-0">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">{{ number_format($stats['rejected']) }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Rejected</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[950px]">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wide text-gray-500 bg-[#FFF6F0]/70">
                            <th class="px-5 py-3.5">#</th>
                            <th class="px-5 py-3.5">Vendor</th>
                            <th class="px-5 py-3.5">Amount</th>
                            <th class="px-5 py-3.5">Bank Details</th>
                            <th class="px-5 py-3.5">Requested</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rows as $w)
                            @php
                                $acct = (string) ($w->account_number ?? '');
                                $masked = $acct !== '' ? '•••• ' . substr($acct, -4) : '—';
                                $chip = match($w->status) {
                                    'pending'  => 'bg-amber-100 text-amber-700',
                                    'approved' => 'bg-blue-100 text-blue-700',
                                    'paid'     => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-600',
                                    default    => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <tr class="hover:bg-[#FFF6F0]/50 transition">
                                <td class="px-5 py-3.5 text-gray-400">{{ $w->id }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-gray-800">{{ $w->vendor_name ?? 'Unknown vendor' }}</div>
                                    <div class="text-[11px] text-gray-400">{{ $w->vendor_email }}</div>
                                </td>
                                <td class="px-5 py-3.5 font-bold text-gray-800 whitespace-nowrap">Rs. {{ number_format($w->amount) }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="text-gray-700">{{ $w->bank_name ?? '—' }}</div>
                                    <div class="text-[11px] text-gray-400 font-mono">{{ $masked }}
                                        @if(!empty($w->account_holder_name)) · {{ $w->account_holder_name }} @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap">
                                    {{ $w->requested_at ? \Carbon\Carbon::parse($w->requested_at)->format('d M Y, h:i A') : '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $chip }}">{{ ucfirst($w->status) }}</span>
                                    @if(!empty($w->transaction_id))
                                        <div class="text-[10px] text-gray-400 mt-1 font-mono">Txn: {{ $w->transaction_id }}</div>
                                    @endif
                                    @if(!empty($w->admin_notes))
                                        <div class="text-[10px] text-gray-400 mt-0.5 max-w-[160px] truncate" title="{{ $w->admin_notes }}">
                                            <i class="fas fa-note-sticky mr-1"></i>{{ $w->admin_notes }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                        @if($w->status === 'pending')
                                            <button onclick="setStatus({{ $w->id }}, 'approved')"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                                <i class="fas fa-check mr-1"></i>Approve
                                            </button>
                                            <button onclick="setStatus({{ $w->id }}, 'rejected')"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-500 hover:bg-red-100 transition">
                                                <i class="fas fa-xmark mr-1"></i>Reject
                                            </button>
                                        @endif
                                        @if(in_array($w->status, ['pending', 'approved']))
                                            <button onclick="markPaid({{ $w->id }})"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition hover:opacity-90"
                                                    style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                                                <i class="fas fa-money-check-dollar mr-1"></i>Mark Paid
                                            </button>
                                        @endif
                                        @if(in_array($w->status, ['paid', 'rejected']))
                                            <span class="text-[11px] text-gray-400">
                                                {{ $w->processed_at ? 'Processed ' . \Carbon\Carbon::parse($w->processed_at)->format('d M Y') : 'No actions' }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>No withdraw requests yet.
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

    async function setStatus(id, status) {
        const verb = status === 'approved' ? 'approve' : 'reject';
        if (!confirm('Are you sure you want to ' + verb + ' this withdraw request?')) return;

        const body = { status };
        if (status === 'rejected') {
            const notes = prompt('Reason / note for rejection (optional):');
            if (notes === null) return; // cancelled the prompt
            if (notes.trim()) body.admin_notes = notes.trim();
        }

        const res = await post('/admin/withdraw-requests/' + id + '/status', body);
        if (res.success) {
            toast(res.message || 'Updated.');
            setTimeout(() => location.reload(), 700);
        } else {
            toast(res.message || 'Update failed.', false);
        }
    }

    async function markPaid(id) {
        if (!confirm('Mark this withdraw request as PAID? Make sure the payment has actually been sent.')) return;

        const txn = prompt('Transaction ID / reference (optional):');
        if (txn === null) return;
        const notes = prompt('Admin notes (optional):');
        if (notes === null) return;

        const body = { status: 'paid' };
        if (txn.trim()) body.transaction_id = txn.trim();
        if (notes.trim()) body.admin_notes = notes.trim();

        const res = await post('/admin/withdraw-requests/' + id + '/status', body);
        if (res.success) {
            toast(res.message || 'Marked as paid.');
            setTimeout(() => location.reload(), 700);
        } else {
            toast(res.message || 'Update failed.', false);
        }
    }
</script>
</body>
</html>
