{{--
    Perfumes subcategory-specific field (Fragrance & Scents).
    Expects an optional $fr array (decoded fragrance_attributes, edit mode).
--}}
@php
    $fr = $fr ?? [];
    $types = ['Eau de Parfum (EDP)','Eau de Toilette (EDT)','Eau de Cologne (EDC)','Eau Fraiche','Parfum','Extrait de Parfum','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Fragrance Type</label>
        <select id="frs_fragrance_type" name="frs_fragrance_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select fragrance type</option>
            @foreach($types as $t)
                <option value="{{ $t }}" {{ ($fr['fragrance_type'] ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>
</div>
