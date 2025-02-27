<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'price',
        'event_id',
        'buyer_id',
    ];

    /**
     * Get the event associated with the ticket.
     */
    public function event(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the buyer (user) associated with the ticket.
     */
    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
