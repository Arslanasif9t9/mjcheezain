<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Category Manager</title>
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
        /* brand pink toggle switch */
        .switch{position:relative;display:inline-flex;align-items:center;cursor:pointer}
        .switch input{position:absolute;opacity:0;width:0;height:0}
        .switch .track{width:36px;height:20px;border-radius:9999px;background:#E5E7EB;transition:background .2s;position:relative;flex-shrink:0}
        .switch .track::after{content:'';position:absolute;top:2px;left:2px;width:16px;height:16px;border-radius:9999px;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.25);transition:transform .2s}
        .switch input:checked + .track{background:linear-gradient(115deg,#FF7DA0,#E85D85)}
        .switch input:checked + .track::after{transform:translateX(16px)}
        .switch input:disabled + .track{opacity:.5}
    </style>
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
                        <i class="fas fa-tags"></i>
                    </span>
                    Category Manager
                </h1>
                <p class="text-sm text-gray-500 mt-1">Manage storefront categories, subcategories, home &amp; cosmetics placement.</p>
            </div>
        </div>

        @php
            $pending = $suggestions->where('status', 'pending');
            $subCount = $subs->flatten()->count();
        @endphp

        <!-- Stats -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white shrink-0" style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">{{ $categories->count() }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Total Categories</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-green-100 text-green-600 shrink-0">
                    <i class="fas fa-circle-check"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">{{ $categories->where('status', 'active')->count() }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Active</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-blue-100 text-blue-600 shrink-0">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">{{ $subCount }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Subcategories</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-amber-100 text-amber-600 shrink-0">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold text-gray-800 leading-tight">{{ $pending->count() }}</div>
                    <div class="text-[11px] text-gray-500 truncate">Pending Suggestions</div>
                </div>
            </div>
        </div>

        <!-- Pending vendor suggestions -->
        @if($pending->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-card p-5 mb-6 border-l-4 border-amber-400">
                <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-lightbulb text-amber-500"></i> Vendor Category Suggestions
                    <span class="bg-amber-100 text-amber-700 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ $pending->count() }} pending</span>
                </h2>
                <div class="space-y-3">
                    @foreach($pending as $s)
                        <div class="flex flex-wrap items-center justify-between gap-3 bg-[#FFF6F0]/60 rounded-xl px-4 py-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-800">
                                    {{ $s->category_name }}
                                    @if($s->subcategory_name)
                                        <span class="text-gray-400 font-normal text-sm"> → {{ $s->subcategory_name }}</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400">
                                    by {{ $s->vendor_name ?? 'Unknown vendor' }} · {{ \Carbon\Carbon::parse($s->created_at)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="suggestionAction({{ $s->id }}, 'approve')"
                                        class="px-4 py-1.5 rounded-lg text-xs font-semibold text-white hover:opacity-90 transition"
                                        style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                                    <i class="fas fa-check mr-1"></i>Approve
                                </button>
                                <button onclick="suggestionAction({{ $s->id }}, 'reject')"
                                        class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-500 hover:bg-red-100 transition">
                                    <i class="fas fa-xmark mr-1"></i>Reject
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Add category -->
        <div class="bg-white rounded-2xl shadow-card p-5 mb-6">
            <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-plus-circle text-brand"></i> Add Category
            </h2>
            <form id="addCategoryForm" class="flex flex-wrap items-end gap-4" onsubmit="addCategory(event)">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Category name</label>
                    <input id="newCatName" type="text" required maxlength="120" placeholder="e.g. Skincare"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand" />
                </div>
                <div class="w-28">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Emoji</label>
                    <input id="newCatEmoji" type="text" maxlength="8" placeholder="💄"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm text-center focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand" />
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600 pb-2.5 cursor-pointer select-none">
                    <input id="newCatHome" type="checkbox" class="w-4 h-4 accent-[#E85D85] rounded"> Show on Home
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-600 pb-2.5 cursor-pointer select-none">
                    <input id="newCatCosmetics" type="checkbox" class="w-4 h-4 accent-[#E85D85] rounded"> Show on Cosmetics
                </label>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition shadow"
                        style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                    <i class="fas fa-plus mr-1"></i> Add Category
                </button>
            </form>
        </div>

        <!-- Category cards -->
        @if($categories->isEmpty())
            <div class="bg-white rounded-2xl shadow-card p-10 text-center text-gray-400">
                <i class="fas fa-tags text-4xl mb-3 text-gray-300 block"></i>
                No categories yet — add your first one above.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4">
                @foreach($categories as $cat)
                    @php
                        $catSubs = $subs->get($cat->id, collect());
                        $count = $counts[$cat->name] ?? 0;
                    @endphp
                    <div class="bg-white rounded-2xl shadow-card p-5 flex flex-col {{ $cat->status === 'active' ? '' : 'opacity-70' }}" id="cat-{{ $cat->id }}">
                        <!-- top row -->
                        <div class="flex items-start justify-between gap-2 mb-4">
                            <div class="flex items-center gap-3 min-w-0">
                                {{-- Home-page tile picture. Click to upload, hover to remove.
                                     With no picture set the storefront shows the emoji circle. --}}
                                <div class="relative shrink-0 group/img">
                                    <button onclick="pickCatImage({{ $cat->id }})" title="Set home-page picture"
                                            class="w-11 h-11 rounded-xl overflow-hidden bg-pink-50 flex items-center justify-center text-xl hover:bg-pink-100 transition">
                                        @if(!empty($cat->image))
                                            <img id="cat-img-{{ $cat->id }}" src="{{ asset($cat->image) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-300 text-sm"></i>
                                        @endif
                                    </button>
                                    @if(!empty($cat->image))
                                        <button onclick="removeCatImage({{ $cat->id }})" title="Remove picture"
                                                class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-white text-red-500 border border-red-100 text-[10px] opacity-0 group-hover/img:opacity-100 transition">
                                            <i class="fas fa-xmark"></i>
                                        </button>
                                    @endif
                                    <input type="file" accept="image/*" class="hidden"
                                           id="cat-file-{{ $cat->id }}"
                                           onchange="uploadCatImage({{ $cat->id }}, this)">
                                </div>

                                <button onclick="editEmoji({{ $cat->id }}, @js($cat->emoji ?? ''))" title="Change emoji (used when no picture is set)"
                                        class="w-11 h-11 rounded-xl bg-pink-50 flex items-center justify-center text-xl hover:bg-pink-100 transition shrink-0">
                                    {{ $cat->emoji ?: '🏷️' }}
                                </button>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-800 truncate">{{ $cat->name }}</span>
                                        <button onclick="editName({{ $cat->id }}, @js($cat->name))" title="Rename"
                                                class="text-gray-300 hover:text-brand transition text-xs shrink-0">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </div>
                                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $count > 0 ? 'bg-pink-50 text-brand' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $count }} {{ $count === 1 ? 'product' : 'products' }}
                                    </span>
                                    @if(($cat->source ?? '') === 'vendor')
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-600">vendor</span>
                                    @endif
                                </div>
                            </div>
                            <button onclick="deleteCategory({{ $cat->id }}, @js($cat->name))" title="Delete category"
                                    class="w-8 h-8 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition shrink-0">
                                <i class="fas fa-trash-can text-sm"></i>
                            </button>
                        </div>

                        <!-- toggles -->
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="bg-[#FFF6F0]/70 rounded-xl px-3 py-2.5 flex flex-col gap-1.5">
                                <span class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold">Active</span>
                                <label class="switch">
                                    <input type="checkbox" {{ $cat->status === 'active' ? 'checked' : '' }}
                                           onchange="toggleField(this, {{ $cat->id }}, 'status')">
                                    <span class="track"></span>
                                </label>
                            </div>
                            <div class="bg-[#FFF6F0]/70 rounded-xl px-3 py-2.5 flex flex-col gap-1.5">
                                <span class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold">Home</span>
                                <label class="switch">
                                    <input type="checkbox" {{ $cat->show_on_home ? 'checked' : '' }}
                                           onchange="toggleField(this, {{ $cat->id }}, 'show_on_home')">
                                    <span class="track"></span>
                                </label>
                            </div>
                            <div class="bg-[#FFF6F0]/70 rounded-xl px-3 py-2.5 flex flex-col gap-1.5">
                                <span class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold">Cosmetics</span>
                                <label class="switch">
                                    <input type="checkbox" {{ $cat->show_on_cosmetics ? 'checked' : '' }}
                                           onchange="toggleField(this, {{ $cat->id }}, 'show_on_cosmetics')">
                                    <span class="track"></span>
                                </label>
                            </div>
                        </div>

                        <!-- subcategories -->
                        <div class="mt-auto border-t border-gray-100 pt-3">
                            <button onclick="toggleSubs({{ $cat->id }})" class="w-full flex items-center justify-between text-sm text-gray-600 hover:text-brand transition font-medium">
                                <span><i class="fas fa-sitemap mr-2 text-gray-300"></i>Subcategories
                                    <span class="text-xs text-gray-400">({{ $catSubs->count() }})</span>
                                </span>
                                <i class="fas fa-chevron-down text-xs transition-transform" id="subChev-{{ $cat->id }}"></i>
                            </button>
                            <div class="hidden mt-3 space-y-2" id="subPanel-{{ $cat->id }}">
                                @forelse($catSubs as $sub)
                                    <div class="flex items-center justify-between bg-[#FFF6F0]/60 rounded-lg px-3 py-2">
                                        <span class="text-sm text-gray-700 truncate">{{ $sub->name }}</span>
                                        <button onclick="deleteSub({{ $sub->id }}, @js($sub->name))" title="Delete subcategory"
                                                class="text-gray-300 hover:text-red-500 transition text-xs shrink-0 ml-2">
                                            <i class="fas fa-xmark"></i>
                                        </button>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-400 px-1">No subcategories yet.</div>
                                @endforelse
                                <form class="flex items-center gap-2 pt-1" onsubmit="addSub(event, {{ $cat->id }})">
                                    <input type="text" required maxlength="120" placeholder="New subcategory…"
                                           class="flex-1 min-w-0 px-3 py-2 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand/40" />
                                    <button type="submit"
                                            class="px-3 py-2 rounded-lg text-xs font-semibold text-white hover:opacity-90 transition shrink-0"
                                            style="background:linear-gradient(115deg,#FF7DA0,#FFC275)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
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
    function reloadSoon() { setTimeout(() => location.reload(), 700); }

    /* ---------- add category ---------- */
    async function addCategory(e) {
        e.preventDefault();
        const name = document.getElementById('newCatName').value.trim();
        if (!name) return;
        const res = await post('/admin/categories', {
            name: name,
            emoji: document.getElementById('newCatEmoji').value.trim(),
            show_on_home: document.getElementById('newCatHome').checked ? 1 : 0,
            show_on_cosmetics: document.getElementById('newCatCosmetics').checked ? 1 : 0,
        });
        if (res.success) { toast(res.message || 'Category added.'); reloadSoon(); }
        else toast(res.message || 'Could not add category.', false);
    }

    /* ---------- edit name / emoji ---------- */
    async function editName(id, current) {
        const name = prompt('Rename category:', current);
        if (name === null) return;
        const trimmed = name.trim();
        if (!trimmed || trimmed === current) return;
        const res = await post('/admin/categories/' + id + '/update', { name: trimmed });
        if (res.success) { toast(res.message || 'Renamed. Products were kept in sync.'); reloadSoon(); }
        else toast(res.message || 'Rename failed.', false);
    }
    async function editEmoji(id, current) {
        const emoji = prompt('Emoji for this category (leave empty to clear):', current);
        if (emoji === null) return;
        const res = await post('/admin/categories/' + id + '/update', { emoji: emoji.trim() });
        if (res.success) { toast(res.message || 'Emoji updated.'); reloadSoon(); }
        else toast(res.message || 'Update failed.', false);
    }

    /* ---------- home-page tile picture ---------- */
    function pickCatImage(id) {
        document.getElementById('cat-file-' + id).click();
    }

    async function uploadCatImage(id, input) {
        const file = input.files[0];
        if (!file) return;

        const fd = new FormData();
        fd.append('image', file);
        try {
            const r = await fetch('/admin/categories/' + id + '/image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: fd
            });
            const res = await r.json();
            toast(res.message || (res.success ? 'Image updated.' : 'Upload failed.'), !!res.success);
            if (res.success) reloadSoon();
        } catch (e) {
            toast('Network error — please retry.', false);
        } finally {
            input.value = '';
        }
    }

    async function removeCatImage(id) {
        if (!confirm('Remove this category picture? The emoji will be shown instead.')) return;
        const res = await post('/admin/categories/' + id + '/image/delete', {});
        toast(res.message || (res.success ? 'Image removed.' : 'Something went wrong.'), !!res.success);
        if (res.success) reloadSoon();
    }

    /* ---------- toggles ---------- */
    async function toggleField(input, id, field) {
        const on = input.checked;
        input.disabled = true;
        const body = field === 'status' ? { status: on ? 'active' : 'inactive' } : { [field]: on ? 1 : 0 };
        const res = await post('/admin/categories/' + id + '/update', body);
        input.disabled = false;
        if (res.success) {
            toast(res.message || 'Category updated.');
            if (field === 'status') {
                document.getElementById('cat-' + id).classList.toggle('opacity-70', !on);
            }
        } else {
            input.checked = !on; // revert
            toast(res.message || 'Update failed.', false);
        }
    }

    /* ---------- delete category ---------- */
    async function deleteCategory(id, name) {
        if (!confirm('Delete category "' + name + '"?\n\nIts subcategories will be removed too. Existing products keep their category name — they just stop being listed under a managed category.')) return;
        const res = await post('/admin/categories/' + id + '/delete', {});
        if (res.success) { toast(res.message || 'Category removed.'); reloadSoon(); }
        else toast(res.message || 'Delete failed.', false);
    }

    /* ---------- subcategories ---------- */
    function toggleSubs(id) {
        const panel = document.getElementById('subPanel-' + id);
        const chev = document.getElementById('subChev-' + id);
        const open = panel.classList.toggle('hidden');
        chev.style.transform = open ? '' : 'rotate(180deg)';
    }
    async function addSub(e, catId) {
        e.preventDefault();
        const input = e.target.querySelector('input');
        const name = input.value.trim();
        if (!name) return;
        const res = await post('/admin/categories/' + catId + '/subcategories', { name });
        if (res.success) { toast(res.message || 'Subcategory added.'); reloadSoon(); }
        else toast(res.message || 'Could not add subcategory.', false);
    }
    async function deleteSub(subId, name) {
        if (!confirm('Delete subcategory "' + name + '"?')) return;
        const res = await post('/admin/subcategories/' + subId + '/delete', {});
        if (res.success) { toast(res.message || 'Subcategory removed.'); reloadSoon(); }
        else toast(res.message || 'Delete failed.', false);
    }

    /* ---------- vendor suggestions ---------- */
    async function suggestionAction(id, action) {
        const msg = action === 'approve'
            ? 'Approve this suggestion? The category (and subcategory, if any) will go live.'
            : 'Reject this suggestion?';
        if (!confirm(msg)) return;
        const res = await post('/admin/category-suggestions/' + id + '/' + action, {});
        if (res.success) { toast(res.message || 'Done.'); reloadSoon(); }
        else toast(res.message || 'Action failed.', false);
    }
</script>
</body>
</html>
