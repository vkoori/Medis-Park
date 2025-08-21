<?php

namespace Modules\Crm\Services;

use Modules\Crm\Dto\UserInfoDto;

/**
 * @todo this class must be update
 */
class CrmService
{
    private readonly string $baseUrl;
    private readonly string $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://example.com';
        $this->apiKey = '123456';
    }

    public function getUserInfo(string $mobile, bool $throwException = false): ?UserInfoDto
    {
        $firstNames = ["John", "Jane", "Alice", "Bob", "Emma", "Michael"];
        $lastNames = ["Smith", "Doe", "Johnson", "Brown", "Davis", "Miller"];
        $emailDomains = ["example.com", "mail.com", "test.com"];
        $streets = ["Main St", "Highland Ave", "Park Blvd", "Oak St", "Maple Ave", "Cedar Rd"];
        $cities = ["New York", "Los Angeles", "Chicago", "Houston", "Phoenix", "Dallas"];
        $states = ["NY", "CA", "IL", "TX", "AZ", "FL"];

        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $street = rand(100, 9999) . " " . $streets[array_rand($streets)];
        $city = $cities[array_rand($cities)];
        $state = $states[array_rand($states)];
        $zip = rand(10000, 99999);

        $email = strtolower($firstName . "." . $lastName . rand(1, 99) . "@" . $emailDomains[array_rand($emailDomains)]);
        $address = "$street, $city, $state $zip";

        return new UserInfoDto(
            mobile: $mobile,
            nationalCode: rand(1, 2) > 1 ? rand(1000000000, 9999999999) : null,
            firstName: $firstName,
            lastName: $lastName,
            email: rand(1, 2) > 1 ? $email : null,
            address: rand(1, 2) > 1 ? $address : null,
        );
    }

    public function updateUserInfo(UserInfoDto $userInfoDto): bool
    {
        return true;
    }

    public function insertUserInfo(UserInfoDto $userInfoDto): bool
    {
        return true;
    }
}
