<?php

namespace App;

use PhpParser\Node\Scalar\String_;

enum Role : String
{
    case USER = 'USER';
    case ADMIN = 'ADMIN';
    case ORGANIZER = 'ORGANIZER';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
