<?php

namespace Modules\Post\Services;

use Carbon\Carbon;
use App\Dto\PostSeenEvent;
use Carbon\CarbonTimeZone;
use App\Traits\ClassResolver;
use Modules\Post\Models\Post;
use Illuminate\Support\Facades\Date;
use Modules\Post\Enums\UserPostVisitEnum;
use Modules\Post\Exceptions\PostVisitExceptions;
use Morilog\Jalali\Jalalian;

class PostVisitService
{
    use ClassResolver;

    public function unlockPostNormally(int $userId): Post
    {
        $post = $this->getPostRepository()->findTodayPost(
            date: Jalalian::now(),
            userId: $userId,
            relations: ['media']
        );

        if (!$post) {
            throw PostVisitExceptions::notPostForVisit();
        }

        if ($post->visited) {
            throw PostVisitExceptions::alreadySeen();
        }

        $this->getUserPostVisitRepository()->create(attributes: [
            'user_id' => $userId,
            'post_id' => $post->id,
            'type' => UserPostVisitEnum::NORMAL,
        ]);
        $post->visited = 1;

        event(new PostSeenEvent(userId: $userId, postId: $post->id));

        return $post;
    }

    public function unlockPostByCoin(int $userId, int $postId)
    {
        $post = $this->getPostRepository()->findPostWithSeen(
            postId: $postId,
            userId: $userId,
            relations: ['media']
        );

        if (!$post) {
            throw PostVisitExceptions::notPostForVisit();
        }

        if ($post->visited) {
            throw PostVisitExceptions::alreadySeen();
        }
    }

    protected function canUserUnlock(int $userId): bool
    {
        $lastUnlockAt = $this->getUserPostVisitRepository()->getLastNormalUnlocked(userId: $userId)?->first_visited_at;
        $currentCycleStart = $this->getStartOfCurrentCycle();

        return is_null($lastUnlockAt) || $lastUnlockAt->lt($currentCycleStart);
    }

    protected function getStartOfCurrentCycle(): Carbon
    {
        $resetTime = config(key: 'post.daily_reset_time');
        $resetTimezone = config(key: 'post.daily_reset_timezone');
        $tz = new CarbonTimeZone(timezone: $resetTimezone);

        $now = Date::now(timezone: $tz);

        $todayReset = Date::createFromFormat(
            format: 'H:i',
            time: $resetTime,
            timezone: $tz
        )->setDate(
            year: $now->year,
            month: $now->month,
            day: $now->day
        );

        return $now->lt(date: $todayReset)
            ? $todayReset->copy()->subDay()
            : $todayReset;
    }
}
