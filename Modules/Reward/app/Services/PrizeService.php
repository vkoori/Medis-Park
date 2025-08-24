<?php

namespace Modules\Reward\Services;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Morilog\Jalali\Jalalian;
use App\Traits\ClassResolver;
use Modules\Reward\Models\Prize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Modules\Reward\Enums\PrizeTypeEnum;
use Modules\Reward\Enums\RewardTypeEnum;
use App\Exceptions\NotImplementedException;
use Modules\Reward\Enums\PrizeUnlockTypeEnum;
use Modules\Reward\Exceptions\PrizeExceptions;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;

class PrizeService
{
    use ClassResolver;

    public function addProduct(int $jYear, int $jMonth, int $productId, int $createdBy): Prize
    {
        $this->getProductService()->show(productId: $productId, relations: []);

        $month = (new Jalalian(year: $jYear, month: $jMonth, day: 1))->format('Y-m');

        $prize = $this->getPrizeRepository()->first(conditions: [
            'type' => PrizeTypeEnum::PRODUCT,
            'prize_reference_id' => $productId,
            'month' => $month,
        ]);
        if ($prize) {
            throw PrizeExceptions::alreadyConverted();
        }

        return DB::transaction(function () use ($productId, $month, $createdBy) {
            $prize = $this->getPrizeRepository()->create(attributes: [
                'type' => PrizeTypeEnum::PRODUCT,
                'prize_reference_id' => $productId,
                'month' => $month,
                'ordering' => $this->nextPrizePriority(jMonth: $month),
            ]);

            $this->getRewardRepository()->create(attributes: [
                'reward_reference_type' => RewardTypeEnum::PRIZE,
                'reward_reference_id' => $prize->id,
                'created_by' => $createdBy,
                'deleted_by' => null,
            ]);

            return $prize;
        });
    }

    public function addCoin(int $jYear, int $jMonth, int $coinAmount, int $createdBy): Prize
    {
        $month = (new Jalalian(year: $jYear, month: $jMonth, day: 1))->format('Y-m');

        return DB::transaction(function () use ($month, $coinAmount, $createdBy) {
            $coinPrize = $this->getPrizeCoinRepository()->create(attributes: [
                'amount' => $coinAmount
            ]);

            $prize = $this->getPrizeRepository()->create(attributes: [
                'type' => PrizeTypeEnum::COIN,
                'prize_reference_id' => $coinPrize->id,
                'month' => $month,
                'ordering' => $this->nextPrizePriority(jMonth: $month),
            ]);

            $this->getRewardRepository()->create(attributes: [
                'reward_reference_type' => RewardTypeEnum::PRIZE,
                'reward_reference_id' => $prize->id,
                'created_by' => $createdBy,
                'deleted_by' => null,
            ]);

            return $prize;
        });
    }

    public function paginate()
    {
        return $this->getPrizeRepository()->paginate(
            relations: ['prizeable']
        );
    }

    public function paginateForCustomer(int $userId)
    {
        return $this->getPrizeRepository()->paginateCustomer(
            userId: $userId,
            relations: ['prizeable']
        );
    }

    public function unlockPrizeByCustomer(int $userId)
    {
        if (!$this->canUserUnlock(userId: $userId)) {
            throw PrizeExceptions::canNotUnlockPrize();
        }

        $month = Jalalian::now()->format('Y-m');

        $lastedUnlock = $this->getPrizeRepository()->latestUnlock(userId: $userId, month: $month);
        $mustBeUnlocked = $this->getPrizeRepository()->first(
            conditions: [
                'month' => $month,
                'ordering' => $lastedUnlock?->ordering + 1
            ],
            relations: ['reward', 'prizeable']
        );

        if (!$mustBeUnlocked) {
            throw PrizeExceptions::allPrizedOpen();
        }

        DB::transaction(function () use ($mustBeUnlocked, $userId) {
            $this->getPrizeUnlockRepository()->create(attributes: [
                'prize_id' => $mustBeUnlocked->id,
                'type' => PrizeUnlockTypeEnum::NORMAL,
                'user_id' => $userId,
            ]);

            $achievement = $this->getRewardUserUnlockedRepository()->create(attributes: [
                'user_id' => $userId,
                'reward_id' => $mustBeUnlocked->reward->id,
                'unlocked_at' => now(),
            ]);

            match ($mustBeUnlocked->type) {
                PrizeTypeEnum::COIN => $this->getCoinTransactionService()->transaction(
                    userId: $userId,
                    amount: $mustBeUnlocked->prizeable->amount,
                    reason: __('reward::messages.reward_reason.coin_prize'),
                    type: TransactionReferenceTypeEnum::REWARD_UNLOCKED,
                    referenceId: $achievement->id
                ),
                PrizeTypeEnum::PRODUCT => $this->getOrderService()->rewardProduct(
                    userId: $userId,
                    productId: $mustBeUnlocked->prize_reference_id
                ),
                default => throw new NotImplementedException()
            };
        });
    }

    protected function nextPrizePriority(string $jMonth): int
    {
        return $this->getPrizeRepository()->count(conditions: [
            'month' => $jMonth,
        ]) + 1;
    }

    protected function canUserUnlock(int $userId): bool
    {
        $lastUnlockAt = $this->getPrizeUnlockRepository()->getLastNormalUnlocked(userId: $userId)?->created_at;
        $currentCycleStart = $this->getStartOfCurrentCycle();

        return is_null($lastUnlockAt) || $lastUnlockAt->lt($currentCycleStart);
    }

    protected function getStartOfCurrentCycle(): Carbon
    {
        $resetTime = config(key: 'reward.daily_reset_time');
        $resetTimezone = config(key: 'reward.daily_reset_timezone');
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
