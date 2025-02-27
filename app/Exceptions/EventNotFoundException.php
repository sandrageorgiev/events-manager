<?php

namespace App\Exceptions;

use Exception;

class EventNotFoundException extends Exception
{
    public function __construct($message = "Event not found")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'error' => $this->getMessage()
        ], 404);
    }
}
