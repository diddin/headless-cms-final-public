<div class="space-y-2">
    <flux:input
        wire:model.defer="body.{{ $index }}.data.url"
        :label="__('Image URL')"
        placeholder="https://..."
    />

    <flux:input
        wire:model.defer="body.{{ $index }}.data.alt"
        :label="__('Alt Text')"
        placeholder="Image description (SEO)"
    />

    <div>
        <label class="text-sm font-medium">Align</label>
        <select wire:model.defer="body.{{ $index }}.data.align" class="w-full mt-1 border rounded">
            <option value="left">Left</option>
            <option value="center">Center</option>
            <option value="right">Right</option>
        </select>
    </div>
</div>
