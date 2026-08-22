{{--
    Reusable "common fields" block for fashion categories that store their
    genuinely-new attributes in vendor_products.fashion_attributes (JSON).
    Currently only shown for Men's Fashion — built as its own partial so the
    other 4 planned fashion categories (Women's Fashion clothing, Kids &
    Baby, Footwear, Fashion Accessories & Bags) can `@include` it as-is later.

    Fields that already have a real vendor_products column (name, brand,
    description, images, video, selling_price, mrp, quantity, pcondition,
    made_in, return_policy, shipping_method/shipping_time, location) are
    NOT repeated here — only genuinely new fields go in fashion_attributes.

    Expects an optional $fa array (decoded fashion_attributes, edit mode).
--}}
@php $fa = $fa ?? []; @endphp

<div class="form-section">
    <h2>Additional Product Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">SKU / Product Code</label>
            <input type="text" id="fa_sku" name="fa_sku" placeholder="e.g. MF-SHIRT-001"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['sku'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Material / Fabric</label>
            <input type="text" id="fa_material" name="fa_material" placeholder="e.g. Cotton, Linen, Denim"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['material'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Color</label>
            <input type="text" id="fa_color" name="fa_color" placeholder="e.g. Navy Blue"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['color'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Pattern / Design</label>
            <input type="text" id="fa_pattern" name="fa_pattern" placeholder="e.g. Striped, Printed, Plain"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['pattern'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Availability</label>
            <select id="fa_availability" name="fa_availability"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="In Stock" {{ ($fa['availability'] ?? '') === 'In Stock' ? 'selected' : '' }}>In Stock</option>
                <option value="Out of Stock" {{ ($fa['availability'] ?? '') === 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
        </div>
        <div>
            <label class="form-label">Weight</label>
            <input type="text" id="fa_weight" name="fa_weight" placeholder="e.g. 350g"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['weight'] ?? '' }}" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <!-- Warranty -->
        <div>
            <label class="form-label">Warranty</label>
            <select id="fa_warranty" name="fa_warranty"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($fa['warranty'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($fa['warranty'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div id="fa_warranty_duration_wrap" class="{{ ($fa['warranty'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Warranty Duration</label>
            <input type="text" id="fa_warranty_duration" name="fa_warranty_duration" placeholder="e.g. 6 months"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['warranty_duration'] ?? '' }}" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <!-- Returnable -->
        <div>
            <label class="form-label">Returnable</label>
            <select id="fa_returnable" name="fa_returnable"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="Yes" {{ ($fa['returnable'] ?? 'Yes') === 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ ($fa['returnable'] ?? '') === 'No' ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <div id="fa_return_policy_wrap" class="{{ ($fa['returnable'] ?? 'Yes') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Return / Exchange Policy</label>
            <input type="text" id="fa_return_exchange_policy" name="fa_return_exchange_policy" placeholder="e.g. 7-day exchange, tags attached"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['return_exchange_policy'] ?? '' }}" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="form-label">Shipping Information</label>
            <input type="text" id="fa_shipping_info" name="fa_shipping_info" placeholder="e.g. Ships in poly bag, folded"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['shipping_info'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Product Tags / Keywords</label>
            <input type="text" id="fa_tags" name="fa_tags" placeholder="Comma separated, e.g. formal, cotton, slim-fit"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fa['tags'] ?? '' }}" />
        </div>
    </div>

    <div class="mt-4">
        <label class="form-label">Care Instructions</label>
        <textarea id="fa_care_instructions" name="fa_care_instructions" placeholder="e.g. Machine wash cold, do not bleach, iron on low heat"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-textarea h-20 resize-none">{{ $fa['care_instructions'] ?? '' }}</textarea>
    </div>

    <div class="mt-4">
        <label class="form-label">Size Guide / Size Chart (optional image)</label>
        <input type="file" id="fa_size_guide" name="fa_size_guide" accept="image/*"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input" />
        @if(!empty($fa['size_guide']))
            <p class="text-xs text-gray-500 mt-1">
                Current: <a href="{{ asset('storage/vendor/products/size_guides/' . $fa['size_guide']) }}" target="_blank" class="text-[#E85D85] underline">view uploaded chart</a>
                (uploading a new one replaces it)
            </p>
            <input type="hidden" name="fa_size_guide_existing" value="{{ $fa['size_guide'] }}">
        @endif
    </div>
</div>
