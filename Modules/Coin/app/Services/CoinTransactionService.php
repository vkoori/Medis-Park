<?php

namespace Modules\Coin\Services;

use App\Exceptions\NotImplementedException;
use App\Traits\ClassResolver;
use Illuminate\Support\Facades\Cache;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;
use Modules\Coin\Enums\TransactionStatusEnum;
use Modules\Coin\Exceptions\TransactionExceptions;
use Modules\Coin\Models\CoinTransaction;

class CoinTransactionService
{
    use ClassResolver;

    public function transaction(
        int $userId,
        int $amount,
        string $reason,
        TransactionReferenceTypeEnum $type,
        int $referenceId,
    ): CoinTransaction {
        $amount = abs(num: $amount);
        [$amount, $status] = match ($type) {
            TransactionReferenceTypeEnum::REWARD_UNLOCKED => [$amount, TransactionStatusEnum::CONFIRMED],
            TransactionReferenceTypeEnum::CRM => [-1 * $amount, TransactionStatusEnum::PENDING],
            TransactionReferenceTypeEnum::ORDER => [-1 * $amount, TransactionStatusEnum::CONFIRMED],
            default => throw new NotImplementedException(),
        };

        if ($amount < 0) {
            Cache::lock("user:{$userId}:transaction", 15)
                ->block(10, function () use ($userId, $amount) {
                    $balance = $this->getUserBalance(userId: $userId);
                    if ($balance < abs($amount)) {
                        throw TransactionExceptions::dontHaveEnoughCoins();
                    }
                });
        }

        return $this->getCoinTransactionRepository()->create(attributes: [
            'user_id' => $userId,
            'amount' => $amount,
            'reason' => $reason,
            'reference_type' => $type,
            'reference_id' => $referenceId,
            'status' => $status,
        ]);
    }

    public function getUserBalance(int $userId): int
    {
        return $this->getCoinTransactionRepository()->getBalance(userId: $userId);
    }

    public function getUserTransactions(int $userId)
    {
        return $this->getCoinTransactionRepository()->get(
            conditions: ['user_id' => $userId]
        );
    }
}
