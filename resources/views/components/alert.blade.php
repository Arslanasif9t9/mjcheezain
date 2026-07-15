@props(['type' => 'info', 'message'])

@php
    $colors = [
        'error' => 'red',
        'success' => 'green',
        'warning' => 'yellow',
        'info' => 'pink'
    ];
    $color = $colors[$type] ?? 'pink';
@endphp

<div class="bg-{{ $color }}-100 border border-{{ $color }}-400 text-{{ $color }}-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ $message }}</span>
    <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>