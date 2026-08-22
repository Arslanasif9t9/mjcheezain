{{--
    Rings subcategory-specific fields (Jewellery & Accessories). Shown only
    when subcategory === "Rings" (toggled by toggleJewelrySubFields() in
    new_product.blade.php). Stored in jewelry_attributes alongside the common
    fields from jewelry_common_fields.blade.php.

    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $stoneTypes = ['Diamond','Ruby','Emerald','Sapphire','Pearl','Moissanite','Cubic Zirconia (CZ)','Opal','Topaz','Amethyst','Garnet','Onyx','Turquoise','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Ring Size</label>
        <input type="text" id="jws_ring_size" name="jws_ring_size" placeholder="e.g. 7, 8, US 9"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $ja['ring_size'] ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Stone Included</label>
        <select id="jws_ring_stone_included" name="jws_ring_stone_included"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="No" {{ ($ja['ring_stone_included'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ ($ja['ring_stone_included'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
        </select>
    </div>
    <div id="jws_ring_stone_type_wrap" class="{{ ($ja['ring_stone_included'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
        <label class="form-label">Stone Type</label>
        <select id="jws_ring_stone_type" name="jws_ring_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select stone type</option>
            @foreach($stoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['ring_stone_type'] ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
</div>
