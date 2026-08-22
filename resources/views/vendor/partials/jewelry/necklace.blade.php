{{--
    Necklace subcategory-specific fields (Jewellery & Accessories).
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $stoneTypes = ['Diamond','Ruby','Emerald','Sapphire','Pearl','Moissanite','Cubic Zirconia (CZ)','Opal','Topaz','Amethyst','Garnet','Onyx','Turquoise','Other'];
    $pendantTypes = ['Heart','Letter-Initial','Religious','Floral','Animal','Geometric','Pearl Pendant','Gemstone Pendant','Coin','Name Pendant','Locket','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Necklace Length (cm)</label>
        <input type="text" id="jws_necklace_length" name="jws_necklace_length" placeholder="e.g. 45"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $ja['necklace_length'] ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Pendant Included</label>
        <select id="jws_necklace_pendant_included" name="jws_necklace_pendant_included"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="No" {{ ($ja['necklace_pendant_included'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ ($ja['necklace_pendant_included'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
        </select>
    </div>
    <div id="jws_necklace_pendant_type_wrap" class="{{ ($ja['necklace_pendant_included'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
        <label class="form-label">Pendant Type</label>
        <select id="jws_necklace_pendant_type" name="jws_necklace_pendant_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select pendant type</option>
            @foreach($pendantTypes as $pt)
                <option value="{{ $pt }}" {{ ($ja['necklace_pendant_type'] ?? '') === $pt ? 'selected' : '' }}>{{ $pt }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Stone Included</label>
        <select id="jws_necklace_stone_included" name="jws_necklace_stone_included"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="No" {{ ($ja['necklace_stone_included'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ ($ja['necklace_stone_included'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
        </select>
    </div>
    <div id="jws_necklace_stone_type_wrap" class="{{ ($ja['necklace_stone_included'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
        <label class="form-label">Stone Type</label>
        <select id="jws_necklace_stone_type" name="jws_necklace_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select stone type</option>
            @foreach($stoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['necklace_stone_type'] ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
</div>
