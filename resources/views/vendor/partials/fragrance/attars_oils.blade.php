{{--
    Attars + Perfume Oils subcategory-specific field (Fragrance & Scents).
    These two subcategories SHARE this one field/partial per the owner's spec.
    Expects an optional $fr array (decoded fragrance_attributes, edit mode).
--}}
@php $fr = $fr ?? []; @endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Alcohol Free</label>
        <select id="frs_alcohol_free" name="frs_alcohol_free"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="No" {{ ($fr['alcohol_free'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ ($fr['alcohol_free'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
        </select>
    </div>
</div>
