<div x-data="{ showModal: @entangle('showModal') }" x-on:category-saved.window="showModal = false">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Categories</h1>
        <button @click="$wire.create()" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add</button>
    </div>

    <table class="w-full">
        <thead>
            <tr class="border-b bg-gray-100 font-bold">
                <th class="text-left p-2">Name</th>
                <th class="text-left p-2">Slug</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr class="border-b">
                    <td class="p-2">{{ $category->name }}</td>
                    <td class="p-2">{{ $category->slug }}</td>
                    <td class="p-2 text-center space-x-2">
                        <button wire:click="edit({{ $category->id }})" class="text-blue-600">Edit</button>|
                        <button wire:click="delete({{ $category->id }})" class="text-red-600 pl-1">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded p-6 w-full max-w-md" @click.away="showModal = false">
            <livewire:categories.category-form :category-id="$editId" wire:key="form-{{ $editId ?? 'new' }}" />
            <div class="text-right mt-4">
                <button @click="showModal = false" class="text-gray-600 hover:text-red-500">Close</button>
            </div>
        </div>
    </div>
</div>