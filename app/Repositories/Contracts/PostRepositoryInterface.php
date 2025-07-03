<?php

namespace App\Repositories\Contracts;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function paginate(int $perPage = 10);
    public function find(int $id): ?Post;
    public function create(array $data): Post|false;
    public function update(Post $post, array $data): Post|false;
    public function delete(Post $post): bool;
}
