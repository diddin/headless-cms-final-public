<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Traits\HandlesRepositoryExceptions;

class PostRepository implements PostRepositoryInterface
{
    use HandlesRepositoryExceptions;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function paginate($perPage = 10)
    {
        return Post::with('categories')->latest()->paginate($perPage);
    }

    public function find(int $id): ?Post
    {
        return Post::with('categories')->find($id);
    }

    public function create(array $data): Post|false
    {
        return $this->safeExecute(
            fn() => Post::create($data),
            'Create Post',
            $data
        );
    }

    public function update(Post $post, array $data): Post|false
    {
        return $this->safeExecute(
            function() use ($post, $data) {
                if ($post->update($data)) {
                    return $post;
                }
            },
            'Update Post',
            $data
        );
    }

    public function delete(Post $post): bool
    {
        return $post->delete();
    }
}
