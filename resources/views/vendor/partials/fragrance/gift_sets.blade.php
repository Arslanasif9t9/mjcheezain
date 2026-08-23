{{--
    Gift Sets subcategory-specific fields (Fragrance & Scents).
    Expects an optional $fr array (decoded fragrance_attributes, edit mode).
--}}
@php
    $fr = $fr ?? [];
    $includedOptions = ['Perfume','Attar','Body Mist','Deodorant','Perfume Oil','Other'];
    $numberOptions = ['2 Items','3 Items','4 Items','5 Items','6+ Items','Other'];
    $included = (array) ($fr['included_items'] ?? []);
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-3">
        <label class="form-label">Included Items</label>
        <div class="flex flex-wrap items-center gap-4 mt-2">
            @foreach($includedOptions as $item)
                <label class="flex items-center gap-1 text-sm">
                    <input type="checkbox" name="frs_included_items[]" value="{{ $item }}" {{ in_array($item, $included) ? 'checked' : '' }}> {{ $item }}
                </label>
            @endforeach
        </div>
    </div>
    <div>
        <label class="form-label">Number of Items</label>
        <select id="frs_number_of_items" name="frs_number_of_items"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select number of items</option>
            @foreach($numberOptions as $n)
                <option value="{{ $n }}" {{ ($fr['number_of_items'] ?? '') === $n ? 'selected' : '' }}>{{ $n }}</option>
            @endforeach
        </select>
    </div>
</div>
