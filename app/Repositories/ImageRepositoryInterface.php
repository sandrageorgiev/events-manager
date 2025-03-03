<?php

namespace App\Repositories;

use App\Models\Event;

interface ImageRepositoryInterface
{
    public function saveImage(Event $event, $file);
}
