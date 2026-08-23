{{--
    Fashion Accessories & Bags category-specific fields — only shown when
    category === "Fashion Accessories & Bags". Stored in
    vendor_products.fashion_attributes alongside the common fields from
    fashion_common_fields.blade.php.

    Two-level selection collapsed into ONE dropdown with two <optgroup>s
    (Bags / Accessories) per the spec — simplest correct approach, avoids a
    third conditional level. All fields below are shown regardless of which
    Product Type was picked (per spec, not further conditionally split).

    Uses a faa_* name/id prefix (see womens_fashion_fields.blade.php for why).

    Expects an optional $fa array (decoded fashion_attributes, edit mode).
--}}
@php $fa = $fa ?? []; @endphp

<div class="form-section">
    <h2>Fashion Accessories &amp; Bags Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Product Type</label>
            <select id="faa_product_type" name="faa_product_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select product type</option>
                <optgroup label="Bags">
                    @foreach(['Handbag','Shoulder Bag','Crossbody','Tote','Clutch','Backpack','Wallet','Travel Bag','Laptop Bag'] as $opt)
                        <option value="{{ $opt }}" {{ ($fa['product_type'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Accessories">
                    @foreach(['Belt','Cap','Hat','Scarf','Gloves','Tie','Sunglasses','Fashion Jewelry','Hair Accessories','Other Fashion Accessories'] as $opt)
                        <option value="{{ $opt }}" {{ ($fa['product_type'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        <div>
            <label class="form-label">Gender</label>
            <select id="faa_gender" name="faa_gender"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select gender</option>
                @foreach(['Men','Women','Kids','Unisex'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['gender'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Size / Dimensions</label>
            <input type="text" id="faa_size_dimensions" name="faa_size_dimensions" placeholder="e.g. 30 x 20 x 10 cm"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['size_dimensions'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Material</label>
            <input type="text" id="faa_material" name="faa_material" placeholder="e.g. Leather, Canvas, Synthetic"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['material'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Closure Type</label>
            <input type="text" id="faa_closure_type" name="faa_closure_type" placeholder="e.g. Zip, Magnetic Snap, Drawstring"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['closure_type'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Strap Type</label>
            <input type="text" id="faa_strap_type" name="faa_strap_type" placeholder="e.g. Shoulder Strap, Handle, Chain"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['strap_type'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Number of Compartments</label>
            <input type="number" id="faa_compartments" name="faa_compartments" min="0" placeholder="e.g. 3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['compartments'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Adjustable Strap</label>
            <select id="faa_adjustable_strap" name="faa_adjustable_strap"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($fa['adjustable_strap'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($fa['adjustable_strap'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div>
            <label class="form-label">Capacity</label>
            <input type="text" id="faa_capacity" name="faa_capacity" placeholder="e.g. 20L, holds A4 files"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['capacity'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Laptop Size Compatibility</label>
            <input type="text" id="faa_laptop_size_compatibility" name="faa_laptop_size_compatibility" placeholder="e.g. up to 15.6 inch (for Laptop Bag)"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['laptop_size_compatibility'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Waterproof</label>
            <select id="faa_waterproof" name="faa_waterproof"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($fa['waterproof'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($fa['waterproof'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div>
            <label class="form-label">Hardware Material</label>
            <input type="text" id="faa_hardware_material" name="faa_hardware_material" placeholder="e.g. Gold-tone metal, Silver-tone metal"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['hardware_material'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Occasion</label>
            <select id="faa_occasion" name="faa_occasion"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select occasion</option>
                @foreach(['Casual','Formal','Party','Wedding','Travel','Office'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['occasion'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Pattern / Design</label>
            <input type="text" id="faa_pattern_design" name="faa_pattern_design" placeholder="e.g. Solid, Printed, Quilted"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['pattern_design'] ?? '' }}" />
        </div>
    </div>
</div>
