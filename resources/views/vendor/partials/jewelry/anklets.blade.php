{{--
    Anklets subcategory-specific fields (Jewellery & Accessories).
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $stoneTypes = ['None','Diamond','Ruby','Emerald','Sapphire','Pearl','Moissanite','Cubic Zirconia (CZ)','Opal','Topaz','Amethyst','Garnet','Onyx','Turquoise','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Anklet Length</label>
        <input type="text" id="jws_anklet_length" name="jws_anklet_length" placeholder="e.g. 9 inch, 10 inch"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $ja['anklet_length'] ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Quantity (Pair / Single)</label>
        <select id="jws_anklet_qty" name="jws_anklet_qty"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select</option>
            <option value="Pair" {{ ($ja['anklet_qty'] ?? '') === 'Pair' ? 'selected' : '' }}>Pair</option>
            <option value="Single" {{ ($ja['anklet_qty'] ?? '') === 'Single' ? 'selected' : '' }}>Single</option>
        </select>
    </div>
    <div>
        <label class="form-label">Stone Type</label>
        <select id="jws_anklet_stone_type" name="jws_anklet_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            @foreach($stoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['anklet_stone_type'] ?? 'None') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
</div>
