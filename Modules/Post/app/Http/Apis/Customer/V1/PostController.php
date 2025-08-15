<?php

namespace Modules\Post\Http\Apis\Customer\V1;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Post\Services\PostService;
use Modules\Post\Http\Resources\PostCustomerResource;

class PostController
{
    public function getMonthly(int $jYear, int $jMonth, PostService $postService)
    {
        $posts = $postService->getCustomerPosts(
            jYear: $jYear,
            jMonth: $jMonth,
            userId: Auth::id()
        );
        
        return SuccessFacade::ok(
            data: PostCustomerResource::collection($posts)
        );
    }
}
