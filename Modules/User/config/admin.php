<?php

$superAdmins = env('SUPER_ADMIN_MOBILE');
$superAdmins = empty($superAdmins) ? [] : explode(',', $superAdmins);

return [
    'super_admins' => $superAdmins,
];
