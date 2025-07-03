<?php

namespace App\Repositories\Contracts;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function paginate(int $perPage = 10);
    public function all();
    public function find(int $id): ?Category;
    public function create(array $data): Category|false;
    public function update(Category $category, array $data): Category|false;
    public function delete(Category $category): bool;
}
