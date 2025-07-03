<div class="space-y-2">
    <flux:input
        wire:model.defer="body.{{ $index }}.data.text"
        :label="__('Heading Text')"
        placeholder="Enter title"
        required
    />

    <div>
        <label class="text-sm font-medium">Size</label>
        <select wire:model.defer="body.{{ $index }}.data.level" class="w-full mt-1 border rounded">
            <option value="1">H1</option>
            <option value="2">H2</option>
            <option value="3">H3</option>
        </select>
    </div>

    <div>
        <label class="text-sm font-medium">Align</label>
        <select wire:model.defer="body.{{ $index }}.data.align" class="w-full mt-1 border rounded">
            <option value="left">Left</option>
            <option value="center">Center</option>
            <option value="right">Right</option>
        </select>
    </div>
</div>
