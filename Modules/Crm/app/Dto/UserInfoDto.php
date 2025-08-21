<?php

namespace Modules\Crm\Dto;

readonly class UserInfoDto
{
    public function __construct(
        private string $mobile,
        private ?string $nationalCode,
        private ?string $email,
        private string $firstName,
        private string $lastName,
        private ?string $address,
    ) {}

    public function getMobile(): string
    {
        return $this->mobile;
    }
    public function getNationalCode(): ?string
    {
        return $this->nationalCode;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getAddress()
    {
        return $this->address;
    }
}
