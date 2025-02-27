<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'available_tickets',
        'longitude',
        'latitude',
        'category',
        'date_start',
        'date_finish',
        'time_start',
        'time_finish',
        'meeting_url',
        'type',
        'price',
        'creator_id', // Assuming creator is related to the User model
    ];

    // Define the relationship with User (Many-to-One)
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Define the relationship with Image (One-to-Many)
    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Image::class);
    }

    // Define the relationship with Tag (Many-to-Many)
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
