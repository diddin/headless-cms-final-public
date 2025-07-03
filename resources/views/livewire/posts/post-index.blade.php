<div x-data="{ showModal: @entangle('showModal') }" x-on:post-saved.window="showModal = false">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Posts</h1>
        <button
            @click="
                editPostId = null;
                $wire.set('postId', null);
                showModal = true
            "
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add
        </button>
    </div>

    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="border-b bg-gray-100 font-bold">
                <th class="text-left p-2">Title</th>
                <th class="text-left p-2">Content</th>
                <th class="text-left p-2">Status</th>
                <th class="text-left p-2">Published At</th>
                <th class="p-2 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">
                        <x-multilang-text :value="$post->title" />
                    </td>
                    <td class="p-2 max-w-[400px] truncate text-gray-600">
                        <x-multilang-text :value="$post->content" />
                    </td>
                    <td class="p-2 capitalize">{{ $post->status }}</td>
                    <td class="p-2 text-sm text-gray-500">
                        {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('j F Y') : '-' }}
                    </td>
                    <td class="p-2 text-center space-x-2">
                        <button
                            @click="
                                editPostId = {{ $post->id }};
                                $wire.set('postId', {{ $post->id }});
                                showModal = true
                            "
                            class="text-blue-600 hover:underline">
                            Edit
                        </button> |
                        <button
                            wire:click="delete({{ $post->id }})"
                            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                            class="text-red-600 hover:underline pl-1">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links() }}
    </div>

    <!-- Modal -->
    <div
        x-show="showModal"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
        style="display: none;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-4 relative">
            <button @click="showModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                ✕
            </button>

            @if ($postId)
                <livewire:posts.post-form :post-id="$postId" wire:key="edit-post-{{ $postId }}" />
            @else
                <livewire:posts.post-form wire:key="create-post" />
            @endif
        </div>
    </div>
</div>
