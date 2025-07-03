<?php

namespace App\Services;

use App\Repositories\Contracts\PageRepositoryInterface;
use App\Models\Page;
use App\DTOs\PageData;

class PageService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected PageRepositoryInterface $repo) {}

    public function list(int $perPage = 10)
    {
        return $this->repo->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->repo->find($id);
    }

    public function store(PageData $dto): Page
    {
        return $this->repo->create([
            'title' => $dto->title,
            'slug' => $dto->slug,
            'body' => $dto->body,
            'status' => $dto->status,
        ]);
    }

    public function update(Page $page, PageData $dto): Page
    {
        return $this->repo->update($page, [
            'title' => $dto->title,
            'slug' => $dto->slug,
            'body' => $dto->body,
            'status' => $dto->status,
        ]);
    }

    public function delete(Page $page): bool
    {
        return $this->repo->delete($page);
    }
}
