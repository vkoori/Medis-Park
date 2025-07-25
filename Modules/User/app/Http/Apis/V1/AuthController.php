<?php

namespace Modules\User\Http\Apis\V1;

use Modules\User\Http\Requests\V1\Auth\UserAccountAccessRequest;
use Modules\User\Services\AuthService;

class AuthController
{
    public function getAccess(UserAccountAccessRequest $request, AuthService $authService)
    {
        $authService->findOrCreateUnverifiedUser(
            mobile: $request->validated('mobile')
        );
        dd(
            $request
        );
    }
}
