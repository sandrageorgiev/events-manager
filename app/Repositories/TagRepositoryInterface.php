<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface
{
    public function all(): Collection;

    public function find(string $name): Tag;

    public function create(array $data): Tag;

    public function update(Tag $tag, array $data): Tag;

    public function delete(Tag $tag): bool;
}
