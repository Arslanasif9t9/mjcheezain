{{--
    Charms subcategory-specific fields (Jewellery & Accessories).
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $charmTypes = ['Heart','Star','Letter','Animal','Flower','Other'];
    $stoneTypes = ['Diamond','Ruby','Emerald','Sapphire','Pearl','Moissanite','Cubic Zirconia (CZ)','Opal','Topaz','Amethyst','Garnet','Onyx','Turquoise','Other'];
    $compatible = (array) ($ja['charm_compatible'] ?? []);
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Charm Type</label>
        <select id="jws_charm_type" name="jws_charm_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select charm type</option>
            @foreach($charmTypes as $ct)
                <option value="{{ $ct }}" {{ ($ja['charm_type'] ?? '') === $ct ? 'selected' : '' }}>{{ $ct }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Compatible With</label>
        <div class="flex items-center gap-4 mt-2">
            <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" name="jws_charm_compatible[]" value="Bracelet" {{ in_array('Bracelet', $compatible) ? 'checked' : '' }}> Bracelet
            </label>
            <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" name="jws_charm_compatible[]" value="Necklace" {{ in_array('Necklace', $compatible) ? 'checked' : '' }}> Necklace
            </label>
        </div>
    </div>
    <div>
        <label class="form-label">Stone Included</label>
        <select id="jws_charm_stone_included" name="jws_charm_stone_included"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="No" {{ ($ja['charm_stone_included'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ ($ja['charm_stone_included'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
        </select>
    </div>
    <div id="jws_charm_stone_type_wrap" class="{{ ($ja['charm_stone_included'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
        <label class="form-label">Stone Type</label>
        <select id="jws_charm_stone_type" name="jws_charm_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select stone type</option>
            @foreach($stoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['charm_stone_type'] ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
</div>
