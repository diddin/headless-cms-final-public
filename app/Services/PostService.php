<?php

namespace App\Services;

use App\Repositories\Contracts\PostRepositoryInterface;
use App\Models\Post;
use App\DTOs\PostData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PostService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected PostRepositoryInterface $postRepository)
    {
        //
    }

    public function list(int $perPage = 10)
    {
        return $this->postRepository->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->postRepository->find($id);
    }

    public function store(PostData $dto): Post
    {
        $post = $this->postRepository->create([
            'title' => $dto->title,
            'slug' => $dto->slug,
            'content' => $dto->content,
            'excerpt' => $dto->excerpt,
            'image' => $dto->image,
            'status' => $dto->status,
            'published_at' => $dto->published_at,
        ]);
    
        $post->categories()->sync($dto->categories);
    
        Log::info('Post created', [
            'post_id' => $post->id,
            'title' => $post->title,
            'user_id' => Auth::user()->id,
        ]);
    
        return $post;
    }

    public function update(Post $post, PostData $dto): Post
    {
        $updated = $this->postRepository->update($post, [
            'title' => $dto->title,
            'slug' => $dto->slug,
            'content' => $dto->content,
            'excerpt' => $dto->excerpt,
            'image' => $dto->image,
            'status' => $dto->status,
            'published_at' => $dto->published_at,
        ]);
    
        $post->categories()->sync($dto->categories);
    
        Log::info('Post updated', [
            'post_id' => $post->id,
            'user_id' => Auth::user()->id,
        ]);
    
        return $updated;
    }

    public function delete(Post $post): bool
    {
        return $this->postRepository->delete($post);
    }
}
