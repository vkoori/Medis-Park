<?php

namespace Modules\User\Http\Apis\Member\V1;

use App\Utils\Response\SuccessFacade;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\V1\Auth\LogoutRequest;
use Modules\User\Services\AuthService;

class AuthController
{
    public function logout(LogoutRequest $request, AuthService $authService)
    {
        $authService->logout(
            currentToken: $request->validated('access'),
            user: Auth::user()
        );

        return SuccessFacade::ok();
    }
}
