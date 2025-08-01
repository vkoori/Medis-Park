<?php

namespace Modules\Crm\Dto;

readonly class UserInfoDto
{
    public function __construct(
        private string $mobile,
        private ?string $nationalCode,
        private string $firstName,
        private string $lastName,
    ) {}

    public function getMobile(): string
    {
        return $this->mobile;
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
}
