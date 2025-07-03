@php
    $align = $data['align'] ?? 'left';
@endphp

@if (!empty($data['url']))
    <div class="mt-4 text-{{ $align }}">
        <img src="{{ $data['url'] }}" alt="{{ $data['alt'] ?? '' }}" class="rounded max-w-full h-auto inline-block">
    </div>
@endif
