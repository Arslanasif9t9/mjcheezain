{{--
    Men's Fashion category-specific fields — only shown when category ===
    "Men's Fashion" (toggled by toggleFashionFields() in new_product.blade.php).
    All values (except the size+stock repeater) are stored in
    vendor_products.fashion_attributes alongside the common fields from
    fashion_common_fields.blade.php.

    TODO: same pattern for Women's Fashion / Kids & Baby / Footwear / Bags & Accessories
    — each will need its own category-specific partial like this one.

    Expects an optional $fa array (decoded fashion_attributes, edit mode)
    and $faSizes (array of ['size'=>..,'stock'=>..], edit mode).
--}}
@php $fa = $fa ?? []; $faSizes = $faSizes ?? []; @endphp

<div class="form-section">
    <h2>Men's Fashion Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Clothing Type</label>
            <select id="fa_clothing_type" name="fa_clothing_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select clothing type</option>
                @foreach(['Shirt','T-Shirt','Jeans','Pants','Shalwar Kameez','Kurta','Suit','Jacket','Coat','Hoodie','Sweater','Shorts','Underwear','Nightwear'] as $ct)
                    <option value="{{ $ct }}" {{ ($fa['clothing_type'] ?? '') === $ct ? 'selected' : '' }}>{{ $ct }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Fit</label>
            <select id="fa_fit" name="fa_fit"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select fit</option>
                @foreach(['Slim','Regular','Loose','Oversized'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['fit'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Season</label>
            <select id="fa_season" name="fa_season"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select season</option>
                @foreach(['Summer','Winter','All Season'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['season'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Sleeve Type</label>
            <select id="fa_sleeve_type" name="fa_sleeve_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select sleeve type</option>
                @foreach(['Full Sleeve','Half Sleeve','Sleeveless','3-Quarter'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['sleeve_type'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Neck Type</label>
            <select id="fa_neck_type" name="fa_neck_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select neck type</option>
                @foreach(['Round Neck','V-Neck','Collar','Mandarin','Hooded'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['neck_type'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Clothing Length</label>
            <select id="fa_clothing_length" name="fa_clothing_length"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select length</option>
                @foreach(['Regular','Long','Short','Cropped'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['clothing_length'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Occasion</label>
            <select id="fa_occasion" name="fa_occasion"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select occasion</option>
                @foreach(['Casual','Formal','Party','Wedding','Sports'] as $opt)
                    <option value="{{ $opt }}" {{ ($fa['occasion'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Gender</label>
            <input type="text" value="Men" readonly
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-input readonly-input bg-gray-100" />
            <input type="hidden" id="fa_gender" name="fa_gender" value="Men">
        </div>
    </div>

    <!-- Size + Stock repeater -->
    <div class="mt-6">
        <div class="flex justify-between items-center mb-2">
            <label class="form-label mb-0">Sizes &amp; Stock</label>
            <button type="button" id="faAddSizeBtn" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Add another size
            </button>
        </div>
        <p class="text-sm text-gray-500 mb-3">Add each size you stock this product in, with how many units you have.</p>
        <div id="faSizeRows" class="space-y-2">
            @foreach($faSizes as $row)
            <div class="fa-size-row grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-3 items-center">
                <select name="fa_size_name[]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-select">
                    <option value="">Select size</option>
                    @foreach(['XS','S','M','L','XL','XXL','Custom'] as $sz)
                        <option value="{{ $sz }}" {{ ($row['size'] ?? '') === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                    @endforeach
                </select>
                <input type="number" name="fa_size_stock[]" min="0" placeholder="Stock" value="{{ $row['stock'] ?? '' }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-input" />
                <button type="button" class="fa-remove-size-btn text-red-600 hover:text-red-800 px-3 py-2" title="Remove"><i class="fas fa-trash-alt"></i></button>
            </div>
            @endforeach
        </div>
    </div>
</div>

<template id="faSizeRowTemplate">
    <div class="fa-size-row grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-3 items-center">
        <select name="fa_size_name[]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-select">
            <option value="">Select size</option>
            <option value="XS">XS</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
            <option value="XXL">XXL</option>
            <option value="Custom">Custom</option>
        </select>
        <input type="number" name="fa_size_stock[]" min="0" placeholder="Stock"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm form-input" />
        <button type="button" class="fa-remove-size-btn text-red-600 hover:text-red-800 px-3 py-2" title="Remove"><i class="fas fa-trash-alt"></i></button>
    </div>
</template>
