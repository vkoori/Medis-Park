<?php

use libphonenumber\PhoneNumberUtil;
use Modules\User\Support\FormattedPhoneNumber;

$superAdmins = env('SUPER_ADMIN_MOBILE', '');
$superAdmins = explode(',', $superAdmins);
$superAdmins = array_map(
    callback: fn($superAdmin): FormattedPhoneNumber => new FormattedPhoneNumber(
        phoneNumber: PhoneNumberUtil::getInstance()->parse(
            numberToParse: trim($superAdmin),
            defaultRegion: 'IR'
        )
    ),
    array: $superAdmins
);

return [
    'super_admins' => $superAdmins,
];
