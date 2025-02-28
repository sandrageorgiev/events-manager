<?php

namespace App\DataTransferObjects;

class Stats
{
    public ?float $revenue;
    public ?int $customers;
    public ?int $tickets;

    public function __construct(?float $revenue, ?int $customers, ?int $tickets)
    {
        $this->revenue = $revenue;
        $this->customers = $customers;
        $this->tickets = $tickets;
    }
}
