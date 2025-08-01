<?php

namespace Modules\User\Http\Apis\General\V1;

use App\Utils\Response\SuccessFacade;
use Modules\User\Http\Requests\V1\Auth\CheckOtpRequest;
use Modules\User\Http\Requests\V1\Auth\RefreshRequest;
use Modules\User\Http\Requests\V1\Auth\UserAccountAccessRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Services\AuthService;

class AuthController
{
    public function getAccess(UserAccountAccessRequest $request, AuthService $authService)
    {
        $user = $authService->findOrCreateUnverifiedUser(
            mobile: $request->validated('mobile')
        );

        return SuccessFacade::ok(
            message: __('user::messages.otp_sent'),
            data: UserResource::make($user)
        );
    }

    public function checkOtp(CheckOtpRequest $request, AuthService $authService)
    {
        $jwt = $authService->loginCustomerByOtp(
            userId: $request->validated('user_id'),
            otp: $request->validated('otp'),
            issuer: $request->validated('issuer')
        );

        return SuccessFacade::ok(data: $jwt);
    }

    public function refresh(RefreshRequest $request, AuthService $authService)
    {
        $jwt = $authService->refresh(
            refreshToken: $request->validated('refresh')
        );

        return SuccessFacade::ok(data: $jwt);
    }
}
