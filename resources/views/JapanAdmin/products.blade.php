<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Japan Products Admin | MJCheezain</title>
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ @filemtime(public_path('css/tailwind.css')) ?: 1 }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #FFF6F0; }
        .brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }
        .brand-input:focus { border-color: #E85D85; box-shadow: 0 0 0 3px rgba(232,93,133,.15); outline: none; }
        .shadow-card { box-shadow: 0 4px 20px rgba(232,93,133,.08); }
    </style>
</head>
<body>

    <!-- Top bar -->
    <header class="brand-gradient sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-5 md:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5 text-white">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <div class="leading-tight">
                    <p class="font-bold text-sm">Japan Products Admin</p>
                    <p class="text-white/80 text-xs">{{ $products->count() }} product{{ $products->count() === 1 ? '' : 's' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="openModal()" class="bg-white text-[#E85D85] font-semibold text-sm px-4 py-2 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-1.5">
                    <i class="fas fa-plus"></i> <span class="hidden sm:inline">Add Product</span>
                </button>
                <a href="/japanadmin/logout" class="bg-white/15 hover:bg-white/25 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors flex items-center gap-1.5">
                    <i class="fas fa-right-from-bracket"></i> <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-5 md:px-8 py-8">

        @if($products->isEmpty())
            <div class="bg-white rounded-2xl shadow-card p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-pink-50 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-2xl text-[#FF7DA0]"></i>
                </div>
                <h3 class="text-gray-800 font-bold text-lg mb-1">No products yet</h3>
                <p class="text-gray-500 text-sm mb-5">Add your first Japan product to get it listed.</p>
                <button onclick="openModal()" class="brand-gradient text-white font-semibold text-sm px-5 py-2.5 rounded-xl">
                    <i class="fas fa-plus mr-1"></i> Add Product
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($products as $p)
                    <div class="bg-white rounded-2xl shadow-card overflow-hidden flex flex-col" id="card-{{ $p->id }}">
                        <div class="h-40 bg-gray-100 relative">
                            <img src="{{ $p->image ?: asset('img/default_img.png') }}" alt="{{ $p->product_name }}"
                                 class="w-full h-full object-cover" onerror="this.src='{{ asset('img/default_img.png') }}'">
                            <span class="absolute top-2 left-2 bg-white/90 text-[10px] font-bold uppercase tracking-wide text-[#E85D85] px-2 py-1 rounded-full">
                                {{ $p->status }}
                            </span>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 mb-1">{{ $p->product_name }}</h3>
                            <p class="text-gray-400 text-xs mb-2">{{ $p->brand }}{{ $p->brand && $p->model ? ' · ' : '' }}{{ $p->model }}</p>
                            <div class="flex items-baseline gap-2 mb-1">
                                <span class="font-extrabold text-[#E85D85]">Rs. {{ number_format($p->selling_price) }}</span>
                                @if($p->mrp && $p->mrp > $p->selling_price)
                                    <span class="text-gray-400 text-xs line-through">Rs. {{ number_format($p->mrp) }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mb-4">Stock: {{ $p->quantity }}</p>
                            <div class="mt-auto flex gap-2">
                                <button onclick='openModal(@json($p))' class="flex-1 border border-gray-200 hover:border-[#E85D85] hover:text-[#E85D85] text-gray-600 text-xs font-semibold py-2 rounded-lg transition-colors">
                                    <i class="fas fa-pen mr-1"></i> Edit
                                </button>
                                <button onclick="deleteProduct({{ $p->id }})" class="flex-1 border border-red-200 hover:bg-red-50 text-red-500 text-xs font-semibold py-2 rounded-lg transition-colors">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <!-- Add/Edit modal -->
    <div id="productModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="brand-gradient px-6 py-4 flex items-center justify-between rounded-t-2xl sticky top-0">
                <h2 id="modalTitle" class="text-white font-bold">Add Product</h2>
                <button onclick="closeModal()" class="text-white/90 hover:text-white text-xl leading-none">&times;</button>
            </div>
            <form id="productForm" class="p-6 space-y-4" onsubmit="return submitProduct(event)">
                <input type="hidden" id="productId" value="">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Product Name *</label>
                    <input type="text" id="product_name" required maxlength="190"
                        class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Brand</label>
                        <input type="text" id="brand" maxlength="120"
                            class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Model</label>
                        <input type="text" id="model" maxlength="120"
                            class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Made In</label>
                        <input type="text" id="made_in" maxlength="120" placeholder="Japan"
                            class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Condition</label>
                        <select id="conditionp"
                            class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                            <option value="New">New</option>
                            <option value="Used">Used</option>
                            <option value="Refurbished">Refurbished</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Price (Rs.) *</label>
                        <input type="number" id="selling_price" required min="0" step="0.01"
                            class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">MRP (Rs.)</label>
                        <input type="number" id="mrp" min="0" step="0.01"
                            class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Stock Qty *</label>
                        <input type="number" id="quantity" required min="0" step="1"
                            class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                    <textarea id="description" rows="3"
                        class="brand-input block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1" id="imageLabel">Image *</label>
                    <input type="file" id="image" accept="image/*"
                        class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-[#E85D85]">
                    <p id="imageHint" class="text-[11px] text-gray-400 mt-1"></p>
                </div>

                <div id="modalError" class="hidden bg-red-50 border-l-4 border-red-400 rounded-lg p-3 text-xs text-red-700"></div>

                <button type="submit" id="submitBtn" class="brand-gradient w-full text-white font-bold text-sm py-3 rounded-xl">
                    Save Product
                </button>
            </form>
        </div>
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
    function reloadSoon() { setTimeout(() => location.reload(), 700); }
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function openModal(product = null) {
        document.getElementById('modalError').classList.add('hidden');
        document.getElementById('productForm').reset();
        if (product) {
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('productId').value = product.id;
            document.getElementById('product_name').value = product.product_name || '';
            document.getElementById('brand').value = product.brand || '';
            document.getElementById('model').value = product.model || '';
            document.getElementById('made_in').value = product.made_in || '';
            document.getElementById('conditionp').value = product.conditionp || '';
            document.getElementById('selling_price').value = product.selling_price || '';
            document.getElementById('mrp').value = product.mrp || '';
            document.getElementById('quantity').value = product.quantity || 0;
            document.getElementById('description').value = product.description || '';
            document.getElementById('image').required = false;
            document.getElementById('imageLabel').textContent = 'Image';
            document.getElementById('imageHint').textContent = 'Leave empty to keep the current image.';
        } else {
            document.getElementById('modalTitle').textContent = 'Add Product';
            document.getElementById('productId').value = '';
            document.getElementById('image').required = true;
            document.getElementById('imageLabel').textContent = 'Image *';
            document.getElementById('imageHint').textContent = '';
        }
        document.getElementById('productModal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    async function submitProduct(e) {
        e.preventDefault();
        const id = document.getElementById('productId').value;
        const submitBtn = document.getElementById('submitBtn');
        const errBox = document.getElementById('modalError');
        errBox.classList.add('hidden');

        const fd = new FormData();
        fd.append('product_name', document.getElementById('product_name').value);
        fd.append('brand', document.getElementById('brand').value);
        fd.append('model', document.getElementById('model').value);
        fd.append('made_in', document.getElementById('made_in').value);
        fd.append('conditionp', document.getElementById('conditionp').value);
        fd.append('selling_price', document.getElementById('selling_price').value);
        fd.append('mrp', document.getElementById('mrp').value);
        fd.append('quantity', document.getElementById('quantity').value);
        fd.append('description', document.getElementById('description').value);
        const imageFile = document.getElementById('image').files[0];
        if (imageFile) fd.append('image', imageFile);

        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        try {
            const url = id ? `/japanadmin/products/${id}/update` : '/japanadmin/products';
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: fd
            });
            const data = await res.json();
            if (data.success) {
                closeModal();
                toast(data.message || 'Saved.');
                reloadSoon();
            } else {
                errBox.textContent = data.message || 'Something went wrong.';
                errBox.classList.remove('hidden');
            }
        } catch (err) {
            errBox.textContent = 'Network error — please retry.';
            errBox.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Product';
        }
        return false;
    }

    async function deleteProduct(id) {
        if (!confirm('Delete this product? This cannot be undone.')) return;
        try {
            const res = await fetch(`/japanadmin/products/${id}/delete`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                toast('Product deleted.');
                document.getElementById('card-' + id)?.remove();
                reloadSoon();
            } else {
                toast(data.message || 'Delete failed.', false);
            }
        } catch (err) {
            toast('Network error — please retry.', false);
        }
    }
</script>
</body>
</html>
