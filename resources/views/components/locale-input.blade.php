@props([
    'field' => '',
    'type' => 'text',
    'label' => '',
    'placeholder' => '',
])

<div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        @if ($type === 'textarea')
            <label class="text-sm font-medium">🇬🇧 {{ $label }} (EN)</label>
            <textarea
                id="{{ $field }}_en"
                wire:model.defer="excerpt.en"
                {{-- wire:model.defer="{{ $field }}.en" --}}
                placeholder="{{ $placeholder }}"
                rows="5"
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm p-3 resize-none transition"
            ></textarea>

            @error($field.'.id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        @else
            <flux:input
                wire:model.defer="{{ $field }}.en"
                :label="__('🇬🇧 ' . $label . ' (EN)')"
                placeholder="{{ $placeholder }}"
                :type="$type"
                class="h-32 resize-y"
            />
        @endif
    </div>
    <div>
        @if ($type === 'textarea')
            <label class="text-sm font-medium">🇮🇩 {{ $label }} (ID)</label>
            <textarea
                id="{{ $field }}_id"
                wire:model.defer="{{ $field }}.id"
                placeholder="{{ $placeholder }}"
                rows="5"
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm p-3 resize-none transition"
            ></textarea>

            @error($field.'.id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        @else
            <flux:input
                wire:model.defer="{{ $field }}.id"
                :label="__('🇮🇩 ' . $label . ' (ID)')"
                placeholder="{{ $placeholder }}"
                :type="$type"
                class="h-32 resize-y"
            />
        @endif
    </div>
</div>