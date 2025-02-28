<?php

namespace App\Dto;

class RegisterDto
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $password;

    // Constructor to initialize the DTO
    public function __construct(string $firstName, string $lastName, string $email, string $password)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
    }

    // Static method to create the DTO from the incoming request
    public static function fromRequest(array $data): self
    {
        return new self(
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password']
        );
    }
}
