<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    use HasFactory;

    protected $table = 'thumbnails';

    protected $fillable = [
        'name',
        'content_type',
        'data'
    ];

    // If you need to cast the binary data to and from base64 for JSON responses
    protected $casts = [
        'data' => 'binary',
    ];
}
