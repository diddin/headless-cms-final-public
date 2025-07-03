@php
    $level = 'h' . ($data['level'] ?? 2);
    $align = $data['align'] ?? 'left';
@endphp

<{{ $level }} class="text-2xl font-bold text-{{ $align }} mt-6">
    {{ $data['text'] ?? '' }}
</{{ $level }}>
