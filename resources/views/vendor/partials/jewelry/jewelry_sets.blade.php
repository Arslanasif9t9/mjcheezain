{{--
    Jewelry Sets subcategory-specific fields (Jewellery & Accessories).
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $stoneTypes = ['None','Diamond','Ruby','Emerald','Sapphire','Pearl','Moissanite','Cubic Zirconia (CZ)','Opal','Topaz','Amethyst','Garnet','Onyx','Turquoise','Other'];
    $occasions = ['Casual','Formal','Party','Wedding','Festive','Other'];
    $includes = (array) ($ja['set_includes'] ?? []);
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-3">
        <label class="form-label">Set Includes</label>
        <div class="flex flex-wrap items-center gap-4 mt-2">
            @foreach(['Necklace','Earrings','Ring','Bracelet'] as $piece)
                <label class="flex items-center gap-1 text-sm">
                    <input type="checkbox" name="jws_set_includes[]" value="{{ $piece }}" {{ in_array($piece, $includes) ? 'checked' : '' }}> {{ $piece }}
                </label>
            @endforeach
        </div>
    </div>
    <div>
        <label class="form-label">Number of Pieces</label>
        <input type="number" id="jws_set_pieces" name="jws_set_pieces" min="0" placeholder="e.g. 3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $ja['set_pieces'] ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Stone Type</label>
        <select id="jws_set_stone_type" name="jws_set_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            @foreach($stoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['set_stone_type'] ?? 'None') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Occasion</label>
        <select id="jws_set_occasion" name="jws_set_occasion"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select occasion</option>
            @foreach($occasions as $occ)
                <option value="{{ $occ }}" {{ ($ja['set_occasion'] ?? '') === $occ ? 'selected' : '' }}>{{ $occ }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Certification</label>
        <input type="text" id="jws_set_certification" name="jws_set_certification" placeholder="e.g. Gold/diamond certification reference"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $ja['set_certification'] ?? '' }}" />
    </div>
</div>
