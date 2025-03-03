<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = [
        'name',
        'content_type',
        'data',
        'event_id',
        'is_base64',// Assuming this is the foreign key for the event relation
    ];

    public function getDecodedDataAttribute()
    {
        if ($this->is_base64) {
            return base64_decode($this->data);
        }
        return $this->data;
    }

//    protected $casts = [
//        'data' => 'binary',
//    ];

    // Define the relationship with Event (Many-to-One)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
