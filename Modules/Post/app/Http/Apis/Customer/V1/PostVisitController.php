<?php

namespace Modules\Post\Http\Apis\Customer\V1;

use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Post\Services\PostVisitService;
use Modules\Post\Http\Resources\PostResource;

class PostVisitController
{
    public function unlockPostNormally(PostVisitService $postVisitService)
    {
        $postVisit = $postVisitService->unlockPostNormally(userId: Auth::id());

        return SuccessFacade::ok(
            data: PostResource::make($postVisit)
        );
    }

    public function unlockedPosts(int $jYear, int $jMonth, PostVisitService $postVisitService)
    {
        $from = new Jalalian(year: $jYear, month: $jMonth, day: 1);
        $toExclusive = (clone $from)->addMonths(months: 1);

        $posts = $postVisitService->unlockedPosts(
            userId: Auth::id(),
            from: $from->toCarbon(),
            toExclusive: $toExclusive->toCarbon()
        );

        return SuccessFacade::ok(
            data: PostResource::collection($posts)
        );
    }
}
