{{--
    Reusable "common fields" block for Jewellery & Accessories — shared across
    all 11 subcategories (Rings, Necklace, Earrings, Bangles, Chain, Pendants,
    Anklets, Nose Pins, Brooches, Charms, Jewelry Sets). Stored in
    vendor_products.jewelry_attributes (JSON). Same pattern as
    fashion_common_fields.blade.php.

    Fields that already have a real vendor_products column (name, brand,
    description, images, video, selling_price, mrp, quantity, pcondition,
    made_in) are NOT repeated here.

    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php $ja = $ja ?? []; @endphp

<div class="form-section">
    <h2>Jewellery Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Material</label>
            <select id="jw_material" name="jw_material"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select material</option>
                @foreach(['Gold','Silver','Platinum','Stainless Steel','Titanium','Brass','Alloy','Artificial','Other'] as $m)
                    <option value="{{ $m }}" {{ ($ja['material'] ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div id="jw_purity_wrap" class="hidden">
            <label class="form-label">Purity</label>
            <select id="jw_purity" name="jw_purity" data-prefill="{{ $ja['purity'] ?? '' }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select purity</option>
            </select>
        </div>
        <div>
            <label class="form-label">Weight (grams)</label>
            <input type="text" id="jw_weight" name="jw_weight" placeholder="e.g. 5.2"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $ja['weight'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Color</label>
            <input type="text" id="jw_color" name="jw_color" placeholder="e.g. Gold, Rose Gold"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $ja['color'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Gender</label>
            <select id="jw_gender" name="jw_gender"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select gender</option>
                @foreach(['Men','Women','Unisex','Kids'] as $g)
                    <option value="{{ $g }}" {{ ($ja['gender'] ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="form-label">Warranty</label>
            <select id="jw_warranty" name="jw_warranty"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($ja['warranty'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($ja['warranty'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div id="jw_warranty_duration_wrap" class="{{ ($ja['warranty'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Warranty Duration</label>
            <input type="text" id="jw_warranty_duration" name="jw_warranty_duration" placeholder="e.g. 1 year"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $ja['warranty_duration'] ?? '' }}" />
        </div>
    </div>
</div>
