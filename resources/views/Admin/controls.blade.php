<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Controls</title>
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
    <style>
        body{font-family:'Poppins',sans-serif}
        .toggle-track { transition: background-color .2s; }
        .toggle-thumb { transition: transform .2s; }
        input:checked + .toggle-track { background: linear-gradient(115deg,#FF7DA0,#FFC275); }
        input:checked + .toggle-track .toggle-thumb { transform: translateX(20px); }
    </style>
</head>
<body class="bg-[#FFF6F0]">
<div class="flex min-h-screen">
    <x-admin.sidebar />

    <main class="flex-1 min-w-0 p-6 lg:p-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <span class="w-11 h-11 rounded-xl flex items-center justify-center text-white" style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                    <i class="fas fa-sliders"></i>
                </span>
                Controls
            </h1>
            <p class="text-sm text-gray-500 mt-1">Site-wide switches. Changes apply immediately, everywhere.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-6 max-w-xl">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fab fa-whatsapp text-[#25D366]"></i> WhatsApp Buy Now
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                        When ON: every "Buy Now" button (auto parts, cosmetics, main products, Japan — for everyone,
                        no matter their role) opens WhatsApp with the order details instead of checkout.
                        Placing a real order through the website (cart checkout included) is also blocked while this is on.
                    </p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                    <input type="checkbox" id="enabledToggle" class="sr-only peer" {{ $whatsappBuyNowEnabled ? 'checked' : '' }}>
                    <div class="toggle-track w-11 h-6 bg-gray-300 rounded-full relative">
                        <div class="toggle-thumb absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow"></div>
                    </div>
                </label>
            </div>

            <div id="numberField" class="{{ $whatsappBuyNowEnabled ? '' : 'opacity-50' }}">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">WhatsApp Number</label>
                <input type="text" id="numberInput" value="{{ $whatsappBuyNowNumber }}" placeholder="92300xxxxxxx"
                       class="block w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand/30"
                       {{ $whatsappBuyNowEnabled ? '' : 'disabled' }}>
                <p class="text-[11px] text-gray-400 mt-1.5">Country code + number, no spaces, no "+" (e.g. 923001234567).</p>
            </div>

            <button id="saveBtn" onclick="saveControls()"
                    class="mt-5 text-white font-bold text-sm px-5 py-2.5 rounded-xl"
                    style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                <i class="fas fa-check mr-1"></i> Save
            </button>
        </div>

        {{-- Account access switches --}}
        <h2 class="text-lg font-bold text-gray-800 mt-8 mb-1">Account Access</h2>
        <p class="text-sm text-gray-500 mb-4">
            Turn sign-in or new registration off for a role. The buttons disappear from the whole site
            <em>and</em> the server refuses the request &mdash; hiding alone is never enough.
            <strong>No account is ever deleted.</strong>
        </p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 max-w-4xl">
            @foreach (['customer' => 'fa-user', 'vendor' => 'fa-store'] as $role => $icon)
                <div class="bg-white rounded-2xl shadow-card p-6" data-role-card="{{ $role }}">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-4">
                        <i class="fas {{ $icon }} text-brand"></i> {{ ucfirst($role) }} Access
                    </h3>

                    <div class="space-y-3">
                        <label class="flex items-center justify-between gap-4 cursor-pointer">
                            <span class="text-sm text-gray-700">Allow sign in</span>
                            <span class="relative inline-flex items-center shrink-0">
                                <input type="checkbox" class="sr-only peer" data-field="login"
                                       {{ $access[$role . '_login'] ? 'checked' : '' }}>
                                <span class="toggle-track w-11 h-6 bg-gray-300 rounded-full relative">
                                    <span class="toggle-thumb absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow"></span>
                                </span>
                            </span>
                        </label>

                        <label class="flex items-center justify-between gap-4 cursor-pointer">
                            <span class="text-sm text-gray-700">Allow new registration</span>
                            <span class="relative inline-flex items-center shrink-0">
                                <input type="checkbox" class="sr-only peer" data-field="register"
                                       {{ $access[$role . '_register'] ? 'checked' : '' }}>
                                <span class="toggle-track w-11 h-6 bg-gray-300 rounded-full relative">
                                    <span class="toggle-thumb absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow"></span>
                                </span>
                            </span>
                        </label>

                        <label class="flex items-start justify-between gap-4 cursor-pointer pt-3 border-t border-gray-100">
                            <span class="text-sm text-gray-700">
                                Sign out everyone already logged in
                                <span class="block text-[11px] text-gray-400 mt-0.5 font-normal">
                                    Only applies while sign-in is off. Ends sessions &mdash; accounts stay safe.
                                </span>
                            </span>
                            <span class="relative inline-flex items-center shrink-0 mt-0.5">
                                <input type="checkbox" class="sr-only peer" data-field="force_logout"
                                       {{ $forceLogout[$role] ? 'checked' : '' }}>
                                <span class="toggle-track w-11 h-6 bg-gray-300 rounded-full relative">
                                    <span class="toggle-thumb absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow"></span>
                                </span>
                            </span>
                        </label>
                    </div>

                    <button onclick="saveAccess('{{ $role }}', this)"
                            class="mt-5 w-full text-white font-bold text-sm py-2.5 rounded-xl"
                            style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                        <i class="fas fa-check mr-1"></i> Save {{ ucfirst($role) }} Access
                    </button>
                </div>
            @endforeach
        </div>
    </main>
</div>

<script>
    /* ---------- helpers (same pattern as Admin/category_manage.blade.php) ---------- */
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

    const enabledToggle = document.getElementById('enabledToggle');
    const numberField = document.getElementById('numberField');
    const numberInput = document.getElementById('numberInput');

    enabledToggle.addEventListener('change', () => {
        numberField.classList.toggle('opacity-50', !enabledToggle.checked);
        numberInput.disabled = !enabledToggle.checked;
    });

    async function saveControls() {
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        const res = await post('/admin/controls', {
            enabled: enabledToggle.checked,
            number: numberInput.value.trim()
        });
        btn.disabled = false;
        if (res.success) {
            toast(res.message || 'Saved.');
        } else {
            toast(res.message || 'Something went wrong.', false);
        }
    }

    async function saveAccess(role, btn) {
        const card = document.querySelector(`[data-role-card="${role}"]`);
        const val = (field) => card.querySelector(`[data-field="${field}"]`).checked;

        btn.disabled = true;
        const res = await post('/admin/controls/access', {
            role: role,
            login: val('login'),
            register: val('register'),
            force_logout: val('force_logout')
        });
        btn.disabled = false;
        toast(res.message || (res.success ? 'Saved.' : 'Something went wrong.'), !!res.success);
    }
</script>
</body>
</html>
