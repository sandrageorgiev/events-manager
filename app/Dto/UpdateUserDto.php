<?php

namespace App\Dto;

class UpdateUserDto
{
    public string $firstName;
    public string $lastName;
    public string $currentPassword;
    public string $newPassword;
    public string $email;

    // Constructor to initialize the DTO
    public function __construct(string $firstName, string $lastName, string $currentPassword, string $newPassword, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
        $this->email = $email;
    }

    // Static method to create the DTO from the incoming request
    public static function fromRequest(array $data): self
    {
        return new self(
            $data['firstName'],
            $data['lastName'],
            $data['currentPassword'],
            $data['newPassword'],
            $data['email']
        );
    }

}
