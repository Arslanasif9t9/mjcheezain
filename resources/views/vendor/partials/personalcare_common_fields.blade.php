{{--
    Reusable "common fields" block for Personal Care & Daily Essentials —
    shared across all 6 subcategories (Men's Essentials, Women's Essentials,
    Couple Essentials, Baby & Kids Essentials, Senior Care Essentials, Family
    Essentials). Stored in vendor_products.personalcare_attributes (JSON).
    Same pattern as jewelry_common_fields.blade.php — no per-subcategory
    forms needed.

    Fields that already have a real vendor_products column (name, brand,
    description, images, video, selling_price, mrp, quantity, pcondition,
    made_in) are NOT repeated here.

    Expects an optional $pc array (decoded personalcare_attributes, edit mode).
--}}
@php $pc = $pc ?? []; @endphp

<div class="form-section">
    <h2>Personal Care Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Material</label>
            <select id="pc_material" name="pc_material"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select material</option>
                @foreach(['Plastic','Silicone','Stainless Steel','Fabric','Bamboo','Rubber','Wood','Other'] as $m)
                    <option value="{{ $m }}" {{ ($pc['material'] ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Color</label>
            <input type="text" id="pc_color" name="pc_color" placeholder="e.g. White, Pink"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $pc['color'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Size</label>
            <input type="text" id="pc_size" name="pc_size" placeholder="e.g. Standard, Travel Size"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $pc['size'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Weight</label>
            <input type="text" id="pc_weight" name="pc_weight" placeholder="e.g. 150g"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $pc['weight'] ?? '' }}" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="form-label">Warranty</label>
            <select id="pc_warranty" name="pc_warranty"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($pc['warranty'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($pc['warranty'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div id="pc_warranty_duration_wrap" class="{{ ($pc['warranty'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Warranty Duration</label>
            <input type="text" id="pc_warranty_duration" name="pc_warranty_duration" placeholder="e.g. 6 months"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $pc['warranty_duration'] ?? '' }}" />
        </div>
    </div>
</div>
