<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use App\DTOs\CategoryData;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $repo) {}

    public function list(int $perPage = 10)
    {
        return $this->repo->paginate($perPage);
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function find(int $id): ?Category
    {
        return $this->repo->find($id);
    }

    public function store(CategoryData $dto): Category|false
    {
        return $this->repo->create([
            'name' => $dto->name,
            'slug' => $dto->slug,
        ]);
    }

    public function update(Category $category, CategoryData $dto): Category|false
    {
        return $this->repo->update($category, [
            'name' => $dto->name,
            'slug' => $dto->slug,
        ]);
    }

    public function delete(Category $category): bool
    {
        return $this->repo->delete($category);
    }
}
