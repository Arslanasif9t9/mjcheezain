{{--
    Footwear category-specific fields — only shown when category ===
    "Footwear". Stored in vendor_products.fashion_attributes alongside the
    common fields from fashion_common_fields.blade.php (SKU, material, color,
    ..., care instructions, size guide — these still broadly apply to shoes
    too, so the common partial is reused as-is; only the clothing-specific
    subcategory fields like Clothing Type/Fit/Sleeve Type live in the OTHER
    partials, so selecting Footwear naturally shows just this block instead).

    Uses a faf_* name/id prefix (see womens_fashion_fields.blade.php for why).

    Expects an optional $fa array (decoded fashion_attributes, edit mode).
--}}
@php $fa = $fa ?? []; @endphp

<div class="form-section">
    <h2>Footwear Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Footwear Type</label>
            <select id="faf_footwear_type" name="faf_footwear_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select footwear type</option>
                @foreach(['Sneakers','Formal Shoes','Boots','Sandals','Slippers','Heels','Flats','Sports Shoes','School Shoes','Loafers','Khussa'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['footwear_type'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Gender</label>
            <select id="faf_gender" name="faf_gender"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select gender</option>
                @foreach(['Men','Women','Kids','Unisex'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['gender'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Shoe Size System</label>
            <select id="faf_shoe_size_system" name="faf_shoe_size_system"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select system</option>
                @foreach(['EU','UK','US'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['shoe_size_system'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Size Number</label>
            <input type="text" id="faf_shoe_size" name="faf_shoe_size" placeholder="e.g. 42"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['shoe_size'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Upper Material</label>
            <select id="faf_upper_material" name="faf_upper_material"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select material</option>
                @foreach(['Leather','Synthetic','Textile','Other'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['upper_material'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Sole Material</label>
            <input type="text" id="faf_sole_material" name="faf_sole_material" placeholder="e.g. Rubber, EVA, TPR"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['sole_material'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Heel Height</label>
            <input type="text" id="faf_heel_height" name="faf_heel_height" placeholder="e.g. 2 inches (for Heels)"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['heel_height'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Closure Type</label>
            <select id="faf_closure_type" name="faf_closure_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select closure</option>
                @foreach(['Lace','Slip-On','Buckle','Velcro','Zip'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['closure_type'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Toe Shape</label>
            <select id="faf_toe_shape" name="faf_toe_shape"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select toe shape</option>
                @foreach(['Round','Pointed','Square','Open'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['toe_shape'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Footwear Width</label>
            <select id="faf_footwear_width" name="faf_footwear_width"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select width</option>
                @foreach(['Narrow','Regular','Wide'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['footwear_width'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Season</label>
            <select id="faf_season" name="faf_season"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select season</option>
                @foreach(['Summer','Winter','All Season'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['season'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Occasion</label>
            <select id="faf_occasion" name="faf_occasion"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select occasion</option>
                @foreach(['Casual','Formal','Party','Sports','Wedding'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['occasion'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Waterproof</label>
            <select id="faf_waterproof" name="faf_waterproof"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($fa['waterproof'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($fa['waterproof'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
    </div>
</div>
