{{--
    Nose Pins subcategory-specific fields (Jewellery & Accessories). Purity
    is driven by the common jw_material/jw_purity fields in
    jewelry_common_fields.blade.php (Gold/Silver only) — no separate purity
    field is defined here, it reuses the shared one.
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $stoneTypes = ['None','Diamond','Ruby','Emerald','Sapphire','Pearl','Moissanite','Cubic Zirconia (CZ)','Opal','Topaz','Amethyst','Garnet','Onyx','Turquoise','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Nose Pin Type</label>
        <select id="jws_nosepin_type" name="jws_nosepin_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select type</option>
            @foreach(['Stud','Hoop','Screw'] as $t)
                <option value="{{ $t }}" {{ ($ja['nosepin_type'] ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Stone Type</label>
        <select id="jws_nosepin_stone_type" name="jws_nosepin_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            @foreach($stoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['nosepin_stone_type'] ?? 'None') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
</div>
<p class="text-xs text-gray-500 mt-2">Purity (10K/14K/.../24K for Gold, or 925/999 for Silver) uses the Material field above — select Gold or Silver there to set it.</p>
