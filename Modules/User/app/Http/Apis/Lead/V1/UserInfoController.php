<?php

namespace Modules\User\Http\Apis\Lead\V1;

use Modules\User\Dto\UserInfoDto;
use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\User\Services\AuthService;
use Modules\User\Services\UserInfoService;
use Modules\User\Http\Resources\UserInfoResource;
use Modules\User\Http\Requests\V1\UserInfo\UserInfoStoreRequest;

class UserInfoController
{
    public function show(UserInfoService $userInfoService)
    {
        $userInfo = $userInfoService->getUserInfo(
            userId: Auth::id()
        );

        return SuccessFacade::ok(
            data: $userInfo
                ? UserInfoResource::make($userInfo)
                : null
        );
    }

    public function storeOrUpdate(
        UserInfoStoreRequest $request,
        UserInfoService $userInfoService,
        AuthService $authService
    ) {
        $dto = UserInfoDto::fromFormRequest(request: $request);

        $userInfo = $userInfoService->updateOrCreate(
            userId: Auth::id(),
            dto: $dto
        );

        $jwt = $authService->regenerateCustomerToken(
            currentToken: $request->validated('access_token'),
            issuer: $request->validated('issuer'),
            user: Auth::user()
        );

        return SuccessFacade::ok(
            data: [
                'user_info' => UserInfoResource::make($userInfo),
                'token' => $jwt
            ]
        );
    }
}
