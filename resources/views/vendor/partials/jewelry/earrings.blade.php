{{--
    Earrings subcategory-specific fields (Jewellery & Accessories).
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $earringTypes = ['Stud','Hoop','Drop','Dangle','Huggie','Chandelier','Ear Cuff','Jhumka','Tassel','Clip-On','Other'];
    $earringColors = ['Gold','Silver','Rose Gold','Black','White','Other'];
    $earringStoneTypes = ['Diamond','Pearl','Other'];
    $earringStoneColors = ['White','Black','Blue','Red','Green','Yellow','Pink','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Earring Type</label>
        <select id="jws_earring_type" name="jws_earring_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select earring type</option>
            @foreach($earringTypes as $et)
                <option value="{{ $et }}" {{ ($ja['earring_type'] ?? '') === $et ? 'selected' : '' }}>{{ $et }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Color</label>
        <select id="jws_earring_color" name="jws_earring_color"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select color</option>
            @foreach($earringColors as $ec)
                <option value="{{ $ec }}" {{ ($ja['earring_color'] ?? '') === $ec ? 'selected' : '' }}>{{ $ec }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Stone Included</label>
        <select id="jws_earring_stone_included" name="jws_earring_stone_included"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="No" {{ ($ja['earring_stone_included'] ?? 'No') === 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ ($ja['earring_stone_included'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
        </select>
    </div>
    <div id="jws_earring_stone_type_wrap" class="{{ ($ja['earring_stone_included'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
        <label class="form-label">Stone Type</label>
        <select id="jws_earring_stone_type" name="jws_earring_stone_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select stone type</option>
            @foreach($earringStoneTypes as $st)
                <option value="{{ $st }}" {{ ($ja['earring_stone_type'] ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>
    <div id="jws_earring_stone_color_wrap" class="{{ ($ja['earring_stone_included'] ?? 'No') === 'Yes' ? '' : 'hidden' }}">
        <label class="form-label">Stone Color</label>
        <select id="jws_earring_stone_color" name="jws_earring_stone_color"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select stone color</option>
            @foreach($earringStoneColors as $sc)
                <option value="{{ $sc }}" {{ ($ja['earring_stone_color'] ?? '') === $sc ? 'selected' : '' }}>{{ $sc }}</option>
            @endforeach
        </select>
    </div>
</div>
