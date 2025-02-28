<?php

namespace App\Dto;

class LoginDto
{
    public string $email;
    public string $password;

    // Constructor to initialize the DTO
    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    // Static method to create the DTO from the incoming request
    public static function fromRequest(array $data): self
    {
        return new self(
            $data['email'],
            $data['password']
        );
    }
}
