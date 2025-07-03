@php
    $align = $data['align'] ?? 'left';
@endphp

<p class="mt-4 text-{{ $align }}">
    {{ $data['text'] ?? '' }}
</p>
