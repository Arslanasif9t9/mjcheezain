{{--
    Reusable "common fields" block for Bags & Luggage — shared across all 9
    subcategories (Handbags, Laptop Bags, Shoulder Bags, Crossbody Bags, Tote
    Bags, Clutches, Wallets, Backpacks, Travel Bags). Stored in
    vendor_products.bags_attributes (JSON). Same pattern as
    jewelry_common_fields.blade.php — no per-subcategory forms needed here.

    Fields that already have a real vendor_products column (name, brand,
    description, images, video, selling_price, mrp, quantity, pcondition,
    made_in) are NOT repeated here.

    Expects an optional $bg array (decoded bags_attributes, edit mode).
--}}
@php $bg = $bg ?? []; @endphp

<div class="form-section">
    <h2>Bag Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Material</label>
            <select id="bg_material" name="bg_material"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select material</option>
                @foreach(['Leather','Faux Leather','Canvas','Nylon','Polyester','Suede','Denim','Jute','Other'] as $m)
                    <option value="{{ $m }}" {{ ($bg['material'] ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Color</label>
            <input type="text" id="bg_color" name="bg_color" placeholder="e.g. Black, Tan"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $bg['color'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Size</label>
            <input type="text" id="bg_size" name="bg_size" placeholder="e.g. Medium, 30x20x10 cm"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $bg['size'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Gender</label>
            <select id="bg_gender" name="bg_gender"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select gender</option>
                @foreach(['Men','Women','Unisex','Kids'] as $g)
                    <option value="{{ $g }}" {{ ($bg['gender'] ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="form-label">Warranty</label>
            <select id="bg_warranty" name="bg_warranty"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($bg['warranty'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($bg['warranty'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div id="bg_warranty_duration_wrap" class="{{ ($bg['warranty'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Warranty Duration</label>
            <input type="text" id="bg_warranty_duration" name="bg_warranty_duration" placeholder="e.g. 6 months"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $bg['warranty_duration'] ?? '' }}" />
        </div>
    </div>
</div>
