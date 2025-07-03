<div class="prose max-w-3xl mx-auto py-12">
    @foreach ($page->body as $block)
        @includeIf('blocks.view.' . $block['type'], ['data' => $block['data']])
    @endforeach
</div>
