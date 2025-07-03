<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Traits\HandlesRepositoryExceptions;
use Throwable;
use Illuminate\Support\Facades\Log;

class CategoryRepository implements CategoryRepositoryInterface
{
    use HandlesRepositoryExceptions;

    public function paginate(int $perPage = 10)
    {
        return Category::latest()->paginate($perPage);
    }

    public function all()
    {
        return Category::all();
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category|false
    {
        return $this->safeExecute(
            fn() => Category::create($data),
            'Create Category',
            $data
        );
    }

    public function update(Category $category, array $data): Category|false
    {
        return $this->safeExecute(
            function() use ($category, $data) {
                if ($category->update($data)) {
                    return $category;
                }
            },
            'Update Category',
            $data
        );
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}
