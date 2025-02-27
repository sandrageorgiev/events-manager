<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name',
        'content_type',
        'data',
        'event_id', // Assuming this is the foreign key for the event relation
    ];

    // Define the relationship with Event (Many-to-One)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
