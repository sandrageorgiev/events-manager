<?php

namespace App;

enum Category : String
{
    case BUSINESS = 'BUSINESS';
    case TECH = 'TECH';
    case COMMUNITY = 'COMMUNITY';
    case EDUCATION = 'EDUCATION';
    case CORPORATE = 'CORPORATE';
    case WORKSHOP = 'WORKSHOP';
    case SOCIAL = 'SOCIAL';
    case CULTURAL = 'CULTURAL';
    case RECREATION = 'RECREATION';
    case ALL = 'ALL';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
