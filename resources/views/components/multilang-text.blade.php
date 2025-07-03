@props([
    'value' => [],
    'locale' => config('locales.default', 'en'),
    'fallback' => true,
    'defaultText' => '-',
])

@php
    // jika value masih string JSON, coba decode
    if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [];
    }
    $output = $value[$locale] ?? null;

    if (!$output && $fallback) {
        // fallback ke nilai pertama jika locale utama tidak tersedia
        $output = collect($value)->first();
    }
@endphp

{{ $output ?? $defaultText }}
