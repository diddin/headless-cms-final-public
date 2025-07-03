<div x-data="{ showModal: @entangle('showModal') }" x-on:page-saved.window="showModal = false">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Pages</h1>
        <button @click="$wire.create()" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add</button>
    </div>

    <table class="w-full">
        <thead>
            <tr class="border-b bg-gray-100 font-bold">
                <th class="text-left p-2">Title</th>
                <th class="text-left p-2">Slug</th>
                <th class="text-left p-2">Status</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $page->title }}</td>
                    <td class="p-2">{{ $page->slug }}</td>
                    <td class="p-2 capitalize">{{ $page->status }}</td>
                    <td class="p-2 text-center space-x-2">
                        <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-green-600">Preview</a> |
                        <button wire:click="edit({{ $page->id }})" class="text-blue-600">Edit</button> |
                        <button wire:click="delete({{ $page->id }})" class="text-red-600 pl-1">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <template x-if="showModal">
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded p-6 w-full max-w-2xl" @click.away="showModal = false">
                
                {{-- Render Livewire Form only when the modal is displayed --}}
                @if ($showModal)
                    <livewire:pages.page-form :page-id="$editId" wire:key="form-{{ $editId ?? 'new' }}" />
                @endif
    
                <div class="text-right mt-4">
                    <button @click="showModal = false" class="text-gray-500 hover:text-red-500">Close</button>
                </div>
            </div>
        </div>
    </template>
</div>