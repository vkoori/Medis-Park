<?php

namespace Modules\Reward\Jobs;

use Illuminate\Support\Str;
use App\Traits\ClassResolver;
use Illuminate\Bus\Queueable;
use App\Dto\UserInfoUpdatedEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Reward\Enums\ProfileLevelEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RewardProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ClassResolver;

    public function __construct(private UserInfoUpdatedEvent $event) {}

    public function handle(): void
    {
        $unlockedLevels = $this
            ->getProfileRewardService()
            ->getAchievementsOfProfile(userId: $this->event->getUserId())
            ->pluck('sub_type');

        // Levels in descending order: BRONZE → SILVER → GOLD
        foreach ([ProfileLevelEnum::BRONZE, ProfileLevelEnum::SILVER, ProfileLevelEnum::GOLD] as $level) {
            if ($unlockedLevels->contains($level->value)) {
                continue;
            }

            $fields = $this->getProfileRewardService()->getProfileFields(level: $level);
            $incomplete = $fields->pluck('key')->filter(function ($field) {
                $method = 'get' . Str::studly($field);
                return empty($this->event->{$method}());
            });

            if ($incomplete->isEmpty()) {
                $this->getProfileRewardService()->unlockProfile(
                    level: $level,
                    userId: $this->event->getUserId()
                );
            } else {
                break;
            }
        }
    }
}
