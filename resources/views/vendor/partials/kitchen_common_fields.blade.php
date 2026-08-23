{{--
    Reusable "common fields" block for Kitchen & Dining — shared across all 9
    subcategories (Cooking Essentials, Baking Essentials, Dining Essentials,
    Drinkware, Food Storage, Kitchen Appliances, Kitchen Tools & Gadgets,
    Serving & Tableware, Kitchen Accessories). Stored in
    vendor_products.kitchen_attributes (JSON). Same pattern as
    jewelry_common_fields.blade.php — no per-subcategory forms needed.

    Fields that already have a real vendor_products column (name, brand,
    description, images, video, selling_price, mrp, quantity, pcondition,
    made_in) are NOT repeated here.

    Expects an optional $kt array (decoded kitchen_attributes, edit mode).
--}}
@php $kt = $kt ?? []; @endphp

<div class="form-section">
    <h2>Kitchen & Dining Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Material</label>
            <select id="kt_material" name="kt_material"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select material</option>
                @foreach(['Stainless Steel','Glass','Plastic','Ceramic','Silicone','Wood','Cast Iron','Aluminum','Melamine','Other'] as $m)
                    <option value="{{ $m }}" {{ ($kt['material'] ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Color</label>
            <input type="text" id="kt_color" name="kt_color" placeholder="e.g. Silver, White"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $kt['color'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Capacity / Size</label>
            <input type="text" id="kt_size" name="kt_size" placeholder="e.g. 1.5 Litre, 24 cm"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $kt['size'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Weight</label>
            <input type="text" id="kt_weight" name="kt_weight" placeholder="e.g. 800g"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $kt['weight'] ?? '' }}" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="form-label">Warranty</label>
            <select id="kt_warranty" name="kt_warranty"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($kt['warranty'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($kt['warranty'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div id="kt_warranty_duration_wrap" class="{{ ($kt['warranty'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Warranty Duration</label>
            <input type="text" id="kt_warranty_duration" name="kt_warranty_duration" placeholder="e.g. 1 year"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $kt['warranty_duration'] ?? '' }}" />
        </div>
    </div>
</div>
