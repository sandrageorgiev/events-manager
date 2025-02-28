<?php

namespace App\Dto;

class PublishTicketsDto
{
    public int $eventId;
    public float $price;
    public int $numberOfTickets;

    // Constructor to initialize the DTO
    public function __construct(int $eventId, float $price, int $numberOfTickets)
    {
        $this->eventId = $eventId;
        $this->price = $price;
        $this->numberOfTickets = $numberOfTickets;
    }

    // Static method to create the DTO from the incoming request
    public static function fromRequest(array $data): self
    {
        return new self(
            $data['eventId'],
            $data['price'],
            $data['numberOfTickets']
        );
    }
}
