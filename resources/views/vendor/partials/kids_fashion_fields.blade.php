{{--
    Kids & Baby Fashion category-specific fields — only shown when category
    === "Kids & Baby Fashion". Stored in vendor_products.fashion_attributes
    alongside the common fields from fashion_common_fields.blade.php.

    Uses a fak_* name/id prefix (see womens_fashion_fields.blade.php for why:
    all fashion sub-partials share one DOM, just hidden/shown, so every
    subcategory partial needs its own prefix to avoid id/name collisions).

    Expects an optional $fa array (decoded fashion_attributes, edit mode).
--}}
@php $fa = $fa ?? []; @endphp

<div class="form-section">
    <h2>Kids &amp; Baby Fashion Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Clothing Type</label>
            <select id="fak_clothing_type" name="fak_clothing_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select clothing type</option>
                @foreach(['Baby Dress','Boys Clothing','Girls Clothing','T-Shirt','Pants','Frock','Kurta','Shalwar Kameez','School Wear','Jacket','Sweater','Nightwear'] as $ct)
                    <option value="{{ $ct }}" {{ ($fa['clothing_type'] ?? '') === $ct ? 'selected' : '' }}>{{ $ct }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Age Group</label>
            <select id="fak_age_group" name="fak_age_group"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select age group</option>
                @foreach(['0–3 Months','3–6 Months','6–12 Months','1–2 Years','2–4 Years','4–6 Years','6–8 Years','8–12 Years','12+ Years'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['age_group'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Size</label>
            <select id="fak_size" name="fak_size"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select size</option>
                @foreach(['XS','S','M','L','XL','XXL','Custom'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['size'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Gender</label>
            <select id="fak_gender" name="fak_gender"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select gender</option>
                @foreach(['Boys','Girls','Unisex'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['gender'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Height Range</label>
            <input type="text" id="fak_height_range" name="fak_height_range" placeholder="e.g. 90-100 cm"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['height_range'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Weight Range</label>
            <input type="text" id="fak_weight_range" name="fak_weight_range" placeholder="e.g. 12-15 kg"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['weight_range'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Season</label>
            <select id="fak_season" name="fak_season"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select season</option>
                @foreach(['Summer','Winter','All Season'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['season'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Occasion</label>
            <select id="fak_occasion" name="fak_occasion"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select occasion</option>
                @foreach(['Casual','Formal','Party','Wedding','School'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['occasion'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Pack Quantity</label>
            <input type="number" id="fak_pack_quantity" name="fak_pack_quantity" min="1" placeholder="e.g. 1"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['pack_quantity'] ?? '' }}" />
        </div>
    </div>

    <div class="mt-4">
        <label class="form-label">Safety / Baby-Friendly Material</label>
        <input type="text" id="fak_safety_material" name="fak_safety_material" placeholder="e.g. Hypoallergenic, chemical-free dye, soft cotton"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
            value="{{ $fa['safety_material'] ?? '' }}" />
    </div>
</div>
