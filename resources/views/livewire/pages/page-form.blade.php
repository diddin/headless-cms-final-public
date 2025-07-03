<div class="max-h-[80vh] overflow-y-auto pr-2">
    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Title -->
        <flux:input
            wire:model.defer="title"
            :label="__('Title')"
            placeholder="Page Title"
            required
        />
        @error('title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <!-- Body (Block Editor) -->
        <div class="space-y-4">
            <label class="block text-sm font-medium text-gray-700">Content Blocks</label>

            @foreach ($body as $index => $block)

                <div wire:key="block-{{ $block['id'] }}" class="p-4 border rounded bg-gray-50 relative">
                    @includeIf('blocks.' . $block['type'], [
                        'data' => &$body[$index]['data'],
                        'index' => $index,
                    ])
                    {{-- @includeIf('blocks.' . $block['type'], ['index' => $index]) --}}
                    <button type="button" wire:click="removeBlock({{ $index }})" class="absolute top-2 right-2 text-red-500 text-xs">✕</button>
                </div>
            @endforeach

            <div class="flex gap-2 mt-2">
                <button type="button" wire:click="addBlock('heading')" class="px-3 py-1 bg-blue-500 text-white text-sm rounded">+ Heading</button>
                <button type="button" wire:click="addBlock('paragraph')" class="px-3 py-1 bg-blue-500 text-white text-sm rounded">+ Paragraph</button>
                <button type="button" wire:click="addBlock('image')" class="px-3 py-1 bg-blue-500 text-white text-sm rounded">+ Image</button>
            </div>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            @error('status') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Save') }}
            </flux:button>
        </div>
    </form>
</div>
