{{--
    Chain subcategory-specific fields (Jewellery & Accessories). Uses the
    common Material/Purity conditional logic from jewelry_common_fields.blade.php
    — no separate stone logic for Chain.
    Expects an optional $ja array (decoded jewelry_attributes, edit mode).
--}}
@php
    $ja = $ja ?? [];
    $chainLengths = ['16 inch (40cm)','18 inch (45cm)','20 inch (50cm)','22 inch (55cm)','24 inch (60cm)','26 inch (65cm)','28 inch (70cm)','30 inch (75cm)','Other'];
    $chainStyles = ['Cable','Rope','Box','Figaro','Curb','Snake','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Chain Length</label>
        <select id="jws_chain_length" name="jws_chain_length"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select length</option>
            @foreach($chainLengths as $cl)
                <option value="{{ $cl }}" {{ ($ja['chain_length'] ?? '') === $cl ? 'selected' : '' }}>{{ $cl }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Chain Style</label>
        <select id="jws_chain_style" name="jws_chain_style"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 form-select">
            <option value="">Select style</option>
            @foreach($chainStyles as $cs)
                <option value="{{ $cs }}" {{ ($ja['chain_style'] ?? '') === $cs ? 'selected' : '' }}>{{ $cs }}</option>
            @endforeach
        </select>
    </div>
</div>
