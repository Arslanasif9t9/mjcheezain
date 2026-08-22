{{--
    Brooches subcategory-specific fields (Jewellery & Accessories).
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $stoneTypes = ['None','Diamond','Ruby','Emerald','Sapphire','Pearl','Moissanite','Cubic Zirconia (CZ)','Opal','Topaz','Amethyst','Garnet','Onyx','Turquoise','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Brooch Shape</label>
        <input type="text" id="jws_brooch_shape" name="jws_brooch_shape" placeholder="e.g. Floral, Bow, Animal"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $ja['brooch_shape'] ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Stone Type</label>
        <select id="jws_brooch_stone_type" name="jws_brooch_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            @foreach($stoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['brooch_stone_type'] ?? 'None') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
</div>
