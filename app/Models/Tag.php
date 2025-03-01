<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];
    protected $primaryKey = 'name';
    protected $keyType = 'string';

    // Define the relationship with Event (Many-to-Many)
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
