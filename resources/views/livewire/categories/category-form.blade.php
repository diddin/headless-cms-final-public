<div>
    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Name -->
        <flux:input
            wire:model.defer="name"
            :label="__('Category Name')"
            placeholder="Example: Technology, Business, etc."
            required
        />
        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <!-- Submit -->
        <div class="flex justify-end">
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Save') }}
            </flux:button>
        </div>
    </form>
</div>
