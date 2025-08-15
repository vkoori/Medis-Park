<?php

namespace Modules\Post\Http\Apis\Customer\V1;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Post\Services\PostVisitService;
use Modules\Post\Http\Resources\PostCustomerResource;

class PostVisitController
{
    public function unlockPostNormally(PostVisitService $postVisitService)
    {
        $post = $postVisitService->unlockPostNormally(userId: Auth::id());

        return SuccessFacade::ok(
            data: PostCustomerResource::make($post)
        );
    }
}
