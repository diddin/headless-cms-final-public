<?php

namespace App\Repositories\Contracts;

use App\Models\Page;

interface PageRepositoryInterface
{
    public function paginate(int $perPage = 10);
    public function find(int $id): ?Page;
    public function create(array $data): Page|false;
    public function update(Page $page, array $data): Page|false;
    public function delete(Page $page): bool;
}
