<?php

namespace App\Repositories\impl;

use App\Models\Tag;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TagRepository implements TagRepositoryInterface
{

    public function all(): Collection
    {
        return Tag::all();
    }

    public function find(string $name): Tag
    {
        return Tag::query()->findOrFail($name);
    }

    public function create(array $data): Tag
    {
        return Tag::query()->create($data);
    }

    public function update(Tag $tag, array $data): Tag
    {
        $tag->update($data);
        return $tag;
    }

    public function delete(Tag $tag): bool
    {
        return $tag->delete();
    }
}
