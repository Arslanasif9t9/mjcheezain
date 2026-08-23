{{--
    Women's Fashion category-specific fields — only shown when category ===
    "Women's Fashion" (toggled by toggleFashionFields()/toggleFashionCategoryFields()
    in new_product.blade.php). Stored in vendor_products.fashion_attributes
    alongside the common fields from fashion_common_fields.blade.php.

    All Men's Fashion fields use the fa_* name/id prefix (mens_fashion_fields.blade.php)
    and this partial sits in the SAME DOM at the same time (just hidden), so
    every field here uses a faw_* prefix instead to avoid id/name collisions —
    same reasoning as Jewellery's per-subcategory jws_ring_*/jws_necklace_*
    prefixes. buildFashionAttributes() reads these faw_* inputs for the
    "Women's Fashion" branch and writes them into the SAME JSON key names
    (clothing_type, fit, ...) as Men's Fashion uses, since only one
    category's data is ever populated per product.

    Expects an optional $fa array (decoded fashion_attributes, edit mode)
    and $faSizes (array of ['size'=>..,'stock'=>..], edit mode).
--}}
@php $fa = $fa ?? []; $faSizes = $faSizes ?? []; @endphp

<div class="form-section">
    <h2>Women's Fashion Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Clothing Type</label>
            <select id="faw_clothing_type" name="faw_clothing_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select clothing type</option>
                @foreach(['Dress','Kurti','Saree','Shalwar Kameez','Abaya','Hijab','Top','Shirt','Trousers','Skirt','Lehenga','Gown','Jacket','Nightwear'] as $ct)
                    <option value="{{ $ct }}" {{ ($fa['clothing_type'] ?? '') === $ct ? 'selected' : '' }}>{{ $ct }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Fit</label>
            <select id="faw_fit" name="faw_fit"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select fit</option>
                @foreach(['Slim','Regular','Loose','Oversized'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['fit'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Sleeve Type</label>
            <select id="faw_sleeve_type" name="faw_sleeve_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select sleeve type</option>
                @foreach(['Full Sleeve','Half Sleeve','Sleeveless','3-Quarter'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['sleeve_type'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Neck Type</label>
            <select id="faw_neck_type" name="faw_neck_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select neck type</option>
                @foreach(['Round Neck','V-Neck','Collar','Mandarin','Hooded','Boat Neck','Sweetheart'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['neck_type'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Dress Length</label>
            <select id="faw_dress_length" name="faw_dress_length"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select length</option>
                @foreach(['Mini','Knee-Length','Midi','Maxi','Floor-Length'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['dress_length'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Season</label>
            <select id="faw_season" name="faw_season"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select season</option>
                @foreach(['Summer','Winter','All Season'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['season'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Occasion</label>
            <select id="faw_occasion" name="faw_occasion"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select occasion</option>
                @foreach(['Casual','Formal','Party','Wedding','Bridal'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['occasion'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Embroidery</label>
            <select id="faw_embroidery" name="faw_embroidery"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($fa['embroidery'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($fa['embroidery'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div>
            <label class="form-label">Lining</label>
            <select id="faw_lining" name="faw_lining"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($fa['lining'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($fa['lining'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div>
            <label class="form-label">Gender</label>
            <input type="text" value="Women" readonly
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-input readonly-input bg-gray-100" />
            <input type="hidden" id="faw_gender" name="faw_gender" value="Women">
        </div>
    </div>

    <!-- Size + Stock repeater -->
    <div class="mt-6">
        <div class="flex justify-between items-center mb-2">
            <label class="form-label mb-0">Sizes &amp; Stock</label>
            <button type="button" id="fawAddSizeBtn" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Add another size
            </button>
        </div>
        <p class="text-sm text-gray-500 mb-3">Add each size you stock this product in, with how many units you have.</p>
        <div id="fawSizeRows" class="space-y-2">
            @foreach($faSizes as $row)
            <div class="faw-size-row grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-3 items-center">
                <select name="faw_size_name[]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-select">
                    <option value="">Select size</option>
                    @foreach(['XS','S','M','L','XL','XXL','Custom'] as $sz)
                        <option value="{{ $sz }}" {{ ($row['size'] ?? '') === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                    @endforeach
                </select>
                <input type="number" name="faw_size_stock[]" min="0" placeholder="Stock" value="{{ $row['stock'] ?? '' }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-input" />
                <button type="button" class="faw-remove-size-btn text-red-600 hover:text-red-800 px-3 py-2" title="Remove"><i class="fas fa-trash-alt"></i></button>
            </div>
            @endforeach
        </div>
    </div>
</div>

<template id="fawSizeRowTemplate">
    <div class="faw-size-row grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-3 items-center">
        <select name="faw_size_name[]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-select">
            <option value="">Select size</option>
            <option value="XS">XS</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
            <option value="XXL">XXL</option>
            <option value="Custom">Custom</option>
        </select>
        <input type="number" name="faw_size_stock[]" min="0" placeholder="Stock"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-input" />
        <button type="button" class="faw-remove-size-btn text-red-600 hover:text-red-800 px-3 py-2" title="Remove"><i class="fas fa-trash-alt"></i></button>
    </div>
</template>
