{{--
    Pendants subcategory-specific fields (Jewellery & Accessories).
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $pendantThemes = ['Religious','Heart','Letter','Floral','Other'];
    $stoneTypes = ['None','Diamond','Ruby','Emerald','Sapphire','Pearl','Moissanite','Cubic Zirconia (CZ)','Opal','Topaz','Amethyst','Garnet','Onyx','Turquoise','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Pendant Shape</label>
        <input type="text" id="jws_pendant_shape" name="jws_pendant_shape" placeholder="e.g. Heart, Oval, Teardrop (manually typed)"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $ja['pendant_shape'] ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Pendant Theme</label>
        <select id="jws_pendant_theme" name="jws_pendant_theme"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select theme</option>
            @foreach($pendantThemes as $pt)
                <option value="{{ $pt }}" {{ ($ja['pendant_theme'] ?? '') === $pt ? 'selected' : '' }}>{{ $pt }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Stone Type</label>
        <select id="jws_pendant_stone_type" name="jws_pendant_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            @foreach($stoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['pendant_stone_type'] ?? 'None') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Chain Included</label>
        <select id="jws_pendant_chain_included" name="jws_pendant_chain_included"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="No" {{ ($ja['pendant_chain_included'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ ($ja['pendant_chain_included'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
        </select>
    </div>
</div>
