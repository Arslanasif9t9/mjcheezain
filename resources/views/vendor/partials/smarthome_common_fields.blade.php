{{--
    Reusable "common fields" block for Smart Home & Gadgets — shared across
    all 5 subcategories (Home Organization & Storage, Cleaning & Hygiene
    Gadgets, Smart & Electrical Gadgets, Kitchen Utility Gadgets, Home
    Convenience Gadgets). Stored in vendor_products.smarthome_attributes
    (JSON). Same pattern as jewelry_common_fields.blade.php — no
    per-subcategory forms needed.

    Fields that already have a real vendor_products column (name, brand,
    description, images, video, selling_price, mrp, quantity, pcondition,
    made_in) are NOT repeated here.

    Expects an optional $sm array (decoded smarthome_attributes, edit mode).
--}}
@php $sm = $sm ?? []; @endphp

<div class="form-section">
    <h2>Smart Home & Gadget Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Material</label>
            <select id="sm_material" name="sm_material"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select material</option>
                @foreach(['Plastic','Metal','ABS','Silicone','Glass','Fabric','Wood','Other'] as $m)
                    <option value="{{ $m }}" {{ ($sm['material'] ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Color</label>
            <input type="text" id="sm_color" name="sm_color" placeholder="e.g. White, Black"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $sm['color'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Size / Dimensions</label>
            <input type="text" id="sm_size" name="sm_size" placeholder="e.g. 20x15x10 cm"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $sm['size'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Weight</label>
            <input type="text" id="sm_weight" name="sm_weight" placeholder="e.g. 350g"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $sm['weight'] ?? '' }}" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="form-label">Warranty</label>
            <select id="sm_warranty" name="sm_warranty"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($sm['warranty'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($sm['warranty'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div id="sm_warranty_duration_wrap" class="{{ ($sm['warranty'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Warranty Duration</label>
            <input type="text" id="sm_warranty_duration" name="sm_warranty_duration" placeholder="e.g. 1 year"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $sm['warranty_duration'] ?? '' }}" />
        </div>
    </div>
</div>
