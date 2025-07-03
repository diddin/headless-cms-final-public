<?php

namespace App\Repositories;

use App\Models\Page;
use App\Repositories\Contracts\PageRepositoryInterface;
use App\Traits\HandlesRepositoryExceptions;

class PageRepository implements PageRepositoryInterface
{
    use HandlesRepositoryExceptions;

    public function paginate(int $perPage = 10)
    {
        return Page::latest()->paginate($perPage);
    }

    public function find(int $id): ?Page
    {
        return Page::find($id);
    }

    public function create(array $data): Page|false
    {
        return $this->safeExecute(
            fn() => Page::create($data),
            'Create Page',
            $data
        );
    }

    public function update(Page $page, array $data): Page|false
    {
        return $this->safeExecute(
            function() use ($page, $data) {
                if ($page->update($data)) {
                    return $page;
                }
            },
            'Update Page',
            $data
        );
    }

    public function delete(Page $page): bool
    {
        return $page->delete();
    }
}
