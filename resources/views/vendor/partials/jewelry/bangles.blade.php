{{--
    Bangles subcategory-specific fields (Jewellery & Accessories).
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php $ja = $ja ?? []; @endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Bangle Size</label>
        <input type="text" id="jws_bangle_size" name="jws_bangle_size" placeholder="e.g. 2.4, 2.6, 2.8"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $ja['bangle_size'] ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Quantity (Pair / Single)</label>
        <select id="jws_bangle_qty" name="jws_bangle_qty"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select</option>
            <option value="1 Pair" {{ ($ja['bangle_qty'] ?? '') === '1 Pair' ? 'selected' : '' }}>1 Pair</option>
            <option value="Single" {{ ($ja['bangle_qty'] ?? '') === 'Single' ? 'selected' : '' }}>Single</option>
        </select>
    </div>
</div>
