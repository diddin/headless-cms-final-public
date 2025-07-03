<div @click.away="showModal = false" class="bg-white p-6 rounded w-full max-w-3xl shadow overflow-y-auto max-h-[90vh]">
    <form wire:submit.prevent="save" enctype="multipart/form-data" class="flex flex-col gap-6">
        <!-- Title -->
        <x-locale-input field="title" label="Title" placeholder="Enter title" />
        @error('title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <!-- Excerpt -->
        <x-locale-input field="excerpt" type="textarea" label="Excerpt" placeholder="Enter excerpt" />
        @error('excerpt') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <!-- Content -->
        <x-locale-input field="content" type="textarea" label="Content" placeholder="Enter content" />
        @error('content') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <!-- Categories Dropdown -->
        <div x-data="{
            open: false,
            selected: @entangle('selectedCategories'),
            categories: @js(\App\Models\Category::all()),
            toggle(id) {
                const i = this.selected.indexOf(id);
                i === -1 ? this.selected.push(id) : this.selected.splice(i, 1);
            },
            isSelected(id) {
                return this.selected.includes(id);
            }
        }">
            <label class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
            <div class="relative">
                <button @click="open = !open" type="button"
                        class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-left shadow-sm focus:ring focus:ring-indigo-200">
                    <template x-if="selected.length === 0">
                        <span class="text-gray-500">Choose Category</span>
                    </template>
                    <template x-if="selected.length > 0">
                        <span x-text="categories.filter(c => selected.includes(c.id)).map(c => c.name).join(', ')"></span>
                    </template>
                </button>

                <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 bg-white border rounded shadow w-full max-h-48 overflow-y-auto">
                    <template x-for="cat in categories" :key="cat.id">
                        <label class="flex items-center px-3 py-2 hover:bg-gray-100">
                            <input type="checkbox" :value="cat.id" :checked="isSelected(cat.id)"
                                @change="toggle(cat.id)"
                                class="mr-2 rounded border-gray-300 shadow-sm">
                            <span x-text="cat.name"></span>
                        </label>
                    </template>
                </div>
            </div>
            @error('selectedCategories') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Image Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Image</label>
            <input type="file" wire:model="newImage" class="mt-1 block w-full" />
            @if ($newImage)
                <img src="{{ $newImage->temporaryUrl() }}" class="mt-2 w-32 rounded">
            @elseif ($image)
                <img src="{{ Storage::url($image) }}" class="mt-2 w-32 rounded">
            @endif
            @error('newImage') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
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

        <!-- Published At -->
        <flux:input
            wire:model.defer="published_at"
            :label="__('Publish At')"
            type="datetime-local"
        />
        @error('published_at') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <!-- Submit Button -->
        <div class="flex justify-end">
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Save') }}
            </flux:button>
        </div>
    </form>

    <!-- Modal close -->
    <div class="text-right mt-4">
        <button @click="showModal = false" class="text-gray-500 hover:text-red-500">Close</button>
    </div>
</div>
