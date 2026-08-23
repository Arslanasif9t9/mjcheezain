{{--
    Reusable "common fields" block for Personal Gym Accessories — shared
    across all 9 subcategories (Gym Gloves, Weight Belts, Lifting Straps,
    Wrist Wraps, Knee Sleeves, Resistance Bands, Skipping Ropes, Yoga Mats,
    Gym Bag). Stored in vendor_products.gym_attributes (JSON). Same pattern
    as jewelry_common_fields.blade.php — no per-subcategory forms needed.

    Fields that already have a real vendor_products column (name, brand,
    description, images, video, selling_price, mrp, quantity, pcondition,
    made_in) are NOT repeated here.

    Expects an optional $gm array (decoded gym_attributes, edit mode).
--}}
@php $gm = $gm ?? []; @endphp

<div class="form-section">
    <h2>Gym Accessory Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Material</label>
            <select id="gm_material" name="gm_material"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select material</option>
                @foreach(['Neoprene','Nylon','Cotton','Rubber','Leather','Foam','Polyester','PVC','Other'] as $m)
                    <option value="{{ $m }}" {{ ($gm['material'] ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Color</label>
            <input type="text" id="gm_color" name="gm_color" placeholder="e.g. Black, Blue"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $gm['color'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Size</label>
            <input type="text" id="gm_size" name="gm_size" placeholder="e.g. M, L, One Size"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $gm['size'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Weight</label>
            <input type="text" id="gm_weight" name="gm_weight" placeholder="e.g. 500g"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $gm['weight'] ?? '' }}" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="form-label">Warranty</label>
            <select id="gm_warranty" name="gm_warranty"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($gm['warranty'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($gm['warranty'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div id="gm_warranty_duration_wrap" class="{{ ($gm['warranty'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Warranty Duration</label>
            <input type="text" id="gm_warranty_duration" name="gm_warranty_duration" placeholder="e.g. 3 months"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $gm['warranty_duration'] ?? '' }}" />
        </div>
    </div>
</div>
