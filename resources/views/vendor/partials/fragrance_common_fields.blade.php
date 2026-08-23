{{--
    Reusable "common fields" block for Fragrance & Scents — shared across all
    6 subcategories (Perfumes, Attars, Body Mists, Deodorants, Perfume Oils,
    Gift Sets). Stored in vendor_products.fragrance_attributes (JSON). Same
    pattern as jewelry_common_fields.blade.php.

    Fields that already have a real vendor_products column (name, brand,
    description, images, video, selling_price, mrp, quantity, pcondition,
    made_in) are NOT repeated here.

    Expects an optional $fr array (decoded fragrance_attributes, edit mode).
--}}
@php $fr = $fr ?? []; @endphp

<div class="form-section">
    <h2>Fragrance Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Volume</label>
            <input type="text" id="fr_volume" name="fr_volume" placeholder="e.g. 50ml, 100ml"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fr['volume'] ?? '' }}" />
        </div>
        <div>
            <label class="form-label">Gender</label>
            <select id="fr_gender" name="fr_gender"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="">Select gender</option>
                @foreach(['Men','Women','Unisex','Kids'] as $g)
                    <option value="{{ $g }}" {{ ($fr['gender'] ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Warranty</label>
            <select id="fr_warranty" name="fr_warranty"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
                <option value="No" {{ ($fr['warranty'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
                <option value="Yes" {{ ($fr['warranty'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div id="fr_warranty_duration_wrap" class="{{ ($fr['warranty'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
            <label class="form-label">Warranty Duration</label>
            <input type="text" id="fr_warranty_duration" name="fr_warranty_duration" placeholder="e.g. No warranty applicable normally"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-input"
                value="{{ $fr['warranty_duration'] ?? '' }}" />
        </div>
    </div>
</div>
