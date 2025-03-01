<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): Event;

    public function create(array $data): Event;

    public function update(Event $event, array $data): Event;

    public function delete(Event $event): bool;
}
