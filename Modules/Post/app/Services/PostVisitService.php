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

class PostVisitService
{
    use ClassResolver;

    public function unlockPostNormally(int $userId): Post
    {
        if (!$this->canUserUnlock(userId: $userId)) {
            throw PostVisitExceptions::canNotUnlockPost();
        }

        $post = $this->getPostRepository()->getRandomAvailablePostForUser(userId: $userId);

        $this->getUserPostVisitRepository()->create(attributes: [
            'user_id' => $userId,
            'post_id' => $post->id,
            'type' => UserPostVisitEnum::NORMAL,
            'calendar_day' => config(key: 'post.daily_reset_time') == '00:00'
                ? $this->getStartOfCurrentCycle()->startOfDay()
                : $this->getStartOfCurrentCycle()->addDay()->startOfDay()
        ]);

        event(new PostSeenEvent(userId: $userId, postId: $post->id));

        return $post;
    }

    public function unlockedPosts(int $userId, Carbon $from, Carbon $toExclusive)
    {
        return $this->getPostRepository()->getUnlockedPosts(
            userId: $userId,
            from: $from,
            toExclusive: $toExclusive
        );
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
