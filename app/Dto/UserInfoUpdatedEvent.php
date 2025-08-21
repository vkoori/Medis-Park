<?php

namespace App\Dto;

readonly class UserInfoUpdatedEvent
{
    public function __construct(
        private int $userId,
        private string $mobile,
        private string $email,
        private ?string $nationalCode,
        private string $firstName,
        private string $lastName,
        private string $address,
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getMobile(): string
    {
        return $this->mobile;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getNationalCode(): ?string
    {
        return $this->nationalCode;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
}
